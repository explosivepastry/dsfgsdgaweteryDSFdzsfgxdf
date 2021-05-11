<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Discord class
 */
class Discord {

    private static $_valid_responses = array('fullsuccess', 'badparameter', 'error', 'invguild', 'invuser', 'notlinked', 'unauthorized', 'invrole');

<<<<<<< refs/remotes/upstream/v2
=======
<<<<<<< update/japanese
    /**
     * Make a curl request to the `BOT_URL` and handle it's return value.
     * 
     * @param string|null $url Path to send request body to.
     * @param string $body JSON encoded request body.
     * @return string|bool Bot response (if request went thru and response was valid), otherwise `false`.
     */
>>>>>>> update discord class (got lost in merge?)
    public static function discordBotRequest($url = '/status', $body = null) {
        $response = Util::curlGetContents(BOT_URL . $url, $body);
<<<<<<< update/japanese

=======
    public static function discordBotRequest($url = '/status', $body = null) {
        $response = Util::curlGetContents(BOT_URL . $url, $body);
>>>>>>> update discord class (got lost in merge?)
        if (in_array($response, self::$_valid_responses)) {
            return $response;
        } else {
            return false;
        }
<<<<<<< refs/remotes/upstream/v2

        // Log unknown error from bot
        Log::getInstance()->log(Log::Action('discord/role_set'), $response);
=======
<<<<<<< update/japanese
        
>>>>>>> update discord class (got lost in merge?)
        return false;
=======
        if (in_array($response, self::$_valid_responses)) {
            return $response;
        } else {
            return false;
        }
>>>>>>> formatting of Discord class return statements
=======
>>>>>>> update discord class (got lost in merge?)
    }

    public static function getDiscordRoleId(DB $db, $group_id) {
        $discord_role_id = $db->get('group_sync', array('website_group_id', '=', $group_id));
<<<<<<< refs/remotes/upstream/v2
=======
<<<<<<< update/japanese
<<<<<<< update/japanese

<<<<<<< refs/remotes/upstream/v2
>>>>>>> formatting of Discord class return statements
=======
=======
>>>>>>> update discord class (got lost in merge?)
>>>>>>> update discord class (got lost in merge?)
        if ($discord_role_id->count()) {
            return $discord_role_id->first()->discord_role_id;
        } else {
            return null;
        }
<<<<<<< refs/remotes/upstream/v2
<<<<<<< refs/remotes/upstream/v2
=======
=======
<<<<<<< update/japanese
>>>>>>> update discord class (got lost in merge?)

        return null;
=======
        if ($discord_role_id->count()) {
            return $discord_role_id->first()->discord_role_id;
        } else {
            return null;
        }
>>>>>>> formatting of Discord class return statements
<<<<<<< refs/remotes/upstream/v2
>>>>>>> formatting of Discord class return statements
=======
=======
>>>>>>> update discord class (got lost in merge?)
>>>>>>> update discord class (got lost in merge?)
    }

    public static function getWebsiteGroup(DB $db, $discord_role_id) {
        $website_group_id = $db->get('group_sync', array('discord_role_id', '=', $discord_role_id));
        if ($website_group_id->count()) {
            $group = $db->get('groups', array('id', '=', $website_group_id->first()->website_group_id));
            if ($group->count()) {
                return $group->first();
            }
        }

        return null;
    }

<<<<<<< refs/remotes/upstream/v2
    public static function updateDiscordRoles(User $user_query, $added, $removed, Language $language, $redirect = true) {

        if (!Util::getSetting(DB::getInstance(), 'discord_integration')) {
            return;
        }
=======
<<<<<<< update/japanese
<<<<<<< update/japanese
    // no doc blocks as these are getting yeeted soon
    public static function removeDiscordRole($user_query, $group, Language $language) {
        if (Util::getSetting(DB::getInstance(), 'discord_integration')) {
            if ($user_query->data()->discord_id != null && $user_query->data()->discord_id != 010) {

                $role_id = self::getDiscordRoleId(DB::getInstance(), $group);

                if ($role_id != null) {
                    $json = self::assembleJson($user_query->data()->discord_id, 'remove_role_id', $role_id);
=======
    public static function updateDiscordRoles(User $user_query, $added, $removed, Language $language, $redirect = true) {

        if (!Util::getSetting(DB::getInstance(), 'discord_integration')) {
            return;
        }
>>>>>>> initial commit (untested)
<<<<<<< refs/remotes/upstream/v2
>>>>>>> initial commit (untested)
=======
=======
    public static function updateDiscordRoles(User $user_query, $added, $removed, Language $language, $redirect = true) {

        if (!Util::getSetting(DB::getInstance(), 'discord_integration')) {
            return;
        }
>>>>>>> update discord class (got lost in merge?)
>>>>>>> update discord class (got lost in merge?)

        if ($user_query->data()->discord_id == null || $user_query->data()->discord_id == 010) {
            return;
        }

<<<<<<< refs/remotes/upstream/v2
<<<<<<< refs/remotes/upstream/v2
        $added_arr = self::assembleGroupArray($added, 'add');
        $removed_arr = self::assembleGroupArray($removed, 'remove');

        if (!count($added_arr) && !count($removed_arr)) {
            return;
        }

        $json = self::assembleJson($user_query->data()->discord_id, $added_arr, $removed_arr);
=======
        $added_json = self::assembleGroupJson($added, 'add');
        $removed_json = self::assembleGroupJson($removed, 'remove');
=======
        $added_arr = self::assembleGroupArray($added, 'add');
        $removed_arr = self::assembleGroupArray($removed, 'remove');
>>>>>>> remove unneeded json_encode + rename vars

        if (!count($added_arr) && !count($removed_arr)) {
            return;
        }

<<<<<<< refs/remotes/upstream/v2
        $json = self::assembleJson($user_query->data()->discord_id, $added_json, $removed_json);
>>>>>>> initial commit (untested)
=======
        $json = self::assembleJson($user_query->data()->discord_id, $added_arr, $removed_arr);
>>>>>>> remove unneeded json_encode + rename vars

        $result = self::discordBotRequest('/roleChange', $json);

        if ($result == 'fullsuccess') {
            return;
        }

        // TODO: Add logging of this, as most people will want to be aware if this is an issue
        if ($result == 'partsuccess') {
            if ($redirect) {
                Session::flash('edit_user_warnings', array($language->get('admin', 'discord_bot_error_hierarchy')));
            }

            return;
        }

        $errors = self::parseErrors($result, $language);

        if ($redirect) {
            Session::flash('edit_user_errors', $errors);
            Redirect::to(URL::build('/panel/users/edit/', 'id=' . Output::getClean($user_query->data()->id)));
            die();
        }

        return $errors;
    }

    public static function saveRoles($roles) {
        $roles = array(json_encode($roles));
        file_put_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache', $roles);
    }

    public static function getRoles() {
        if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache')) {
            return json_decode(file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('discord_roles') . '.cache'), true);
        }
<<<<<<< refs/remotes/upstream/v2
        
=======
<<<<<<< update/japanese
<<<<<<< update/japanese

=======
        
>>>>>>> formatting of Discord class return statements
<<<<<<< refs/remotes/upstream/v2
>>>>>>> formatting of Discord class return statements
=======
=======
        
>>>>>>> update discord class (got lost in merge?)
>>>>>>> update discord class (got lost in merge?)
        return array();
    }

    private static function parseErrors($result, Language $language) {
        $errors = array();

        if ($result === false) {
            // This happens when the url is invalid OR the bot is unreachable (down, firewall, etc) OR they have `allow_url_fopen` disabled in php.ini OR the bot returned a new error (they should always check logs)
            $errors[] = $language->get('user', 'discord_communication_error');
            $errors[] = $language->get('admin', 'discord_bot_check_logs');
        } else {
            if (in_array($result, self::$_valid_responses)) {
                $errors[] = $language->get('admin', 'discord_bot_error_' . $result);
            } else {
                // This should never happen
                $errors[] = $language->get('user', 'discord_unknown_error');
            }
        }

        return $errors;
    }

<<<<<<< refs/remotes/upstream/v2
<<<<<<< refs/remotes/upstream/v2
    private static function assembleGroupArray($groups, $action) {
=======
    private static function assembleGroupJson($groups, $action) {
>>>>>>> initial commit (untested)
=======
    private static function assembleGroupArray($groups, $action) {
>>>>>>> remove unneeded json_encode + rename vars
        $return = array();

        foreach ($groups as $group) {
            $discord_id = self::getDiscordRoleId(DB::getInstance(), $group);

            if ($discord_id == null) {
                continue;
            }

            $return[] = [
                'id' => $discord_id,
                'action' => $action
            ];
        }

        return $return;
    }
    
<<<<<<< refs/remotes/upstream/v2
    private static function assembleJson($user_id, $added_arr, $removed_arr) {
=======
<<<<<<< update/japanese
<<<<<<< update/japanese
<<<<<<< update/japanese
    // no docblock as this is revamped in PR
    private static function assembleJson($user_id, $action, $role_id) {
=======
    private static function assembleJson($user_id, $added_json, $removed_json) {
>>>>>>> initial commit (untested)
<<<<<<< refs/remotes/upstream/v2
>>>>>>> initial commit (untested)
=======
=======
    private static function assembleJson($user_id, $added_arr, $removed_arr) {
>>>>>>> remove unneeded json_encode + rename vars
<<<<<<< refs/remotes/upstream/v2
>>>>>>> remove unneeded json_encode + rename vars
=======
=======
    private static function assembleJson($user_id, $added_arr, $removed_arr) {
>>>>>>> update discord class (got lost in merge?)
>>>>>>> update discord class (got lost in merge?)
        // TODO cache or define() website api key and discord guild id
        $return = array();
        $return['guild_id'] = trim(Output::getClean(Util::getSetting(DB::getInstance(), 'discord')));
        $return['user_id'] = $user_id;
        $return['api_key'] = trim(Output::getClean(Util::getSetting(DB::getInstance(), 'mc_api_key')));
<<<<<<< refs/remotes/upstream/v2
<<<<<<< refs/remotes/upstream/v2
        $return['roles'] = array_merge($added_arr, $removed_arr);
=======
        $return['roles'] = json_encode(array_merge($added_json, $removed_json));
>>>>>>> initial commit (untested)
=======
        $return['roles'] = array_merge($added_arr, $removed_arr);
>>>>>>> remove unneeded json_encode + rename vars
        return json_encode($return);
    }
}
