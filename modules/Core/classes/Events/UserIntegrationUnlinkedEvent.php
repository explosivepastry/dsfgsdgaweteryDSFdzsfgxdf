<?php

class UserIntegrationUnlinkedEvent extends AbstractEvent implements DiscordDispatchable {

    public User $user;
    public IntegrationBase $integration;
    public IntegrationUser $integration_user;

    public function __construct(IntegrationUser $integration_user) {
        $this->user = $integration_user->getUser();
        $this->integration = $integration_user->getIntegration();
        $this->integration_user = $integration_user;
    }

    public static function name(): string {
        return 'unlinkIntegrationUser';
    }

    public static function description(): array {
        return ['admin', 'user_unlink_integration_hook_info'];
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->username($this->user->getDisplayname() . ' | ' . SITE_NAME)
            ->avatarUrl($this->user->getAvatar(128, true))
            ->embed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->description($language->get('user', 'user_has_unlinked_integration', [
                        'user' => $this->user->getDisplayname(),
                        'integration' => $this->integration->getName(),
                    ]))
                    ->url(URL::getSelfURL() . ltrim($this->user->getProfileURL(), '/'));
            });
    }
}