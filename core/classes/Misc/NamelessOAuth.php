<?php

use League\OAuth2\Client\Provider\AbstractProvider;

/**
 * OAuth utility class.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class NamelessOAuth extends Instanceable
{
    private array $_providers = [];
    private array $_provider_instances = [];

    private DB $_db;

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    /**
     * Add an OAuth provider to the system.
     *
     * @param string $name          The name of the provider (Discord, Google, etc).
     * @param string $module        Name of the module which registered this provider.
     * @param array  $data          Metadata about the provider: class, user_id_name, scope_id_name, icon
     * @param array  $extra_options Extra options to pass to the provider constructor. Example: keycloak needs some specific options
     */
    public function registerProvider(string $name, string $module, array $data, array $extra_options = []): void
    {
        $this->_providers[$name] = array_merge(['module' => $module, 'extra_options' => $extra_options], $data);
    }

    /**
     * Get an array of all registered provider names and their data.
     *
     * @return array An array of all registered OAuth providers.
     */
    public function getProviders(): array
    {
        return $this->_providers;
    }

    /**
     * Get or create an instance of a specific provider.
     *
     * @param  string     $provider The provider name
     * @return null|array An array of registered OAuth provider.
     */
    public function getProvider(string $provider): ?array
    {
        if (array_key_exists($provider, $this->_providers)) {
            return $this->_providers[$provider];
        }

        return null;
    }

    /**
     * Get an array of provider names and their URL & icon.
     *
     * @return array Array of provider names and their instances
     */
    public function getProvidersAvailable(): array
    {
        $providers = [];
        foreach ($this->_providers as $provider_name => $provider_data) {
            if (!$this->isSetup($provider_name)) {
                continue;
            }

            $provider = $this->getProviderInstance($provider_name);

            $providers[$provider_name] = [
                'url' => $provider->getAuthorizationUrl([
                    'scope' => $provider_data['scopes'] ?? [
                        $provider_data['scope_id_name'],
                        'email',
                    ],
                ]),
                'display_name' => $provider_data['display_name'] ?? ucfirst($provider_name),
                'icon' => $provider_data['icon'] ?? null,
                'logo_url' => $provider_data['logo_url'] ?? null,
                'logo_css' => isset($provider_data['logo_css'])
                    ? $this->formatCss($provider_data['logo_css'])
                    : null,
                'button_css' => isset($provider_data['button_css'])
                    ? $this->formatCss($provider_data['button_css'])
                    : null,
                'text_css' => isset($provider_data['text_css'])
                    ? $this->formatCss($provider_data['text_css'])
                    : null,
            ];
        }

        return $providers;
    }

    /**
     * Get or create an instance of a specific provider.
     *
     * @param  string           $provider The provider name
     * @return AbstractProvider The provider instance
     */
    public function getProviderInstance(string $provider): AbstractProvider
    {
        if (!array_key_exists($provider, $this->_providers)) {
            throw new RuntimeException("Unknown provider: $provider");
        }

        if (isset($this->_provider_instances[$provider])) {
            return $this->_provider_instances[$provider];
        }

        [$clientId, $clientSecret] = $this->getCredentials($provider);
        $url = rtrim(URL::getSelfURL(), '/') . URL::build('/oauth', 'provider=' . urlencode($provider), 'non-friendly');
        $options = [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $url,
        ];

        $options = array_merge($options, $this->_providers[$provider]['extra_options']);

        return $this->_provider_instances[$provider] = new $this->_providers[$provider]['class']($options);
    }

    /**
     * Determine if the email returned from a provider is verified on their end.
     * Used during registration to auto verify emails (if they enter the same one as the provider returned).
     *
     * @param  string $provider      The provider name
     * @param  array  $provider_user The provider user data (aka resource owner)
     * @return bool   Whether the email returned from the provider is verified or not
     */
    public function hasVerifiedEmail(string $provider, array $provider_user): bool
    {
        if (!array_key_exists($provider, $this->_providers)) {
            throw new RuntimeException("Unknown provider: $provider");
        }

        return $this->_providers[$provider]['verify_email']($provider_user);
    }

    /**
     * Determine if a provider is enabled (different from setup!).
     *
     * @param  string $provider The provider name
     * @return bool   If the provider is enabled
     */
    public function isEnabled(string $provider): bool
    {
        return $this->_db->get('oauth', ['provider', $provider])->first()->enabled == '1';
    }

    /**
     * Set a provider as enabled or disabled (`1` or `0` respectively).
     *
     * @param string $provider The provider name
     * @param int    $enabled  Whether to enable or disable the provider
     */
    public function setEnabled(string $provider, int $enabled): void
    {
        $this->_db->query('UPDATE nl2_oauth SET enabled = ? WHERE provider = ?', [$enabled, $provider]);
    }

    /**
     * Determine if a provider is setup.
     * A provider is considered setup if it has a client ID and a client secret set.
     *
     * @param  string $provider The provider name
     * @return bool   If the provider is setup
     */
    public function isSetup(string $provider): bool
    {
        [$client_id, $client_secret] = $this->getCredentials($provider);

        return $client_id !== null && $client_id !== '' && $client_secret !== '';
    }

    /**
     * Get the array key for a specific providers client ID.
     * Discord uses `id` and Google uses `sub`, so we have to be able to differentiate.
     *
     * @param  string $provider The provider name
     * @return string The array key for the provider's client ID
     */
    public function getUserIdName(string $provider): string
    {
        if (array_key_exists($provider, $this->_providers)) {
            return $this->_providers[$provider]['user_id_name'];
        }

        throw new RuntimeException("Unknown provider: $provider");
    }

    /**
     * Get the client ID and client secret for a specific provider.
     *
     * @param  string $provider The provider name
     * @return array  The configured credentials for this provider
     */
    public function getCredentials(string $provider): array
    {
        $data = $this->_db->get('oauth', ['provider', $provider])->first();

        return [
            $data->client_id,
            $data->client_secret,
        ];
    }

    /**
     * Update the client ID and client secret for a specific provider.
     *
     * @param string $provider      The provider name
     * @param string $client_id     The new client ID
     * @param string $client_secret The new client secret
     */
    public function setCredentials(string $provider, string $client_id, string $client_secret): void
    {
        $this->_db->query(
            'INSERT INTO nl2_oauth (provider, client_id, client_secret) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE client_id=?, client_secret=?',
            [$provider, $client_id, $client_secret, $client_id, $client_secret]
        );
    }

    /**
     * Format an array of CSS rules into a string, appending `!important` to each rule.
     *
     * @param  array  $css CSS rule => value array
     * @return string The CSS string
     */
    private function formatCss(array $css): string
    {
        return implode(' ', array_map(static function ($rule, $value) {
            return $rule . ': ' . $value . ' !important;';
        }, array_keys($css), array_values($css)));
    }
}
