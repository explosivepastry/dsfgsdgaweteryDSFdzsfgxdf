<?php

/**
 * TODO: Add description
 *
 * @package Modules\Discord Integration\Endpoints
 * @author UNKNOWN
 * @version UNKNOWN
 * @license MIT
 */
class SubmitDiscordRoleListEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/submit-role-list';
        $this->_module = 'Discord Integration';
        $this->_description = 'Update NamelessMC\'s list of your Discord guild\'s roles.';
        $this->_method = 'POST';
    }

    /**
     * @param Nameless2API $api
     *
     * @return void
     * @throws Exception
     */
    public function execute(Nameless2API $api): void {
        $roles = $_POST['roles'] ?? [];

        try {
            Discord::saveRoles($roles);
        } catch (Exception $e) {
            $api->throwError(DiscordApiErrors::ERROR_UNABLE_TO_UPDATE_DISCORD_ROLES, $e->getMessage(), 500);
        }

        $api->returnArray(['message' => Discord::getLanguageTerm('discord_settings_updated')]);
    }
}
