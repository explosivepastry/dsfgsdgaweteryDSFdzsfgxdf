<?php

class UserProfilePostCreatedEvent extends AbstractEvent implements DiscordDispatchable {

    public User $poster;
    public User $profile_user;
    public string $content;

    public function __construct(User $poster, User $profile_user, string $content) {
        $this->poster = $poster;
        $this->profile_user = $profile_user;
        $this->content = $content;
    }

    public static function name(): string {
        return 'userNewProfilePost';
    }

    public static function description(): array {
        return ['admin', 'user_new_profile_post_hook_info'];
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->setUsername($this->poster->data()->username . ' | ' . SITE_NAME)
            ->setAvatarUrl($this->poster->getAvatar(128, true))
            ->addEmbed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->setDescription($language->get('user', 'x_posted_on_y_profile', [
                        'poster' => $this->poster->getDisplayname(),
                        'user' => $this->profile_user->getDisplayname(),
                    ]))
                    ->setUrl(URL::getSelfURL() . ltrim(URL::build('/profile/' . urlencode($this->profile_user->getDisplayname(true)) . '/#post-' . urlencode(DB::getInstance()->lastId())), '/'))
                    ->addField($language->get('general', 'content'), Text::embedSafe($this->content));
            });
    }
}