<?php

interface HasWidgetSettings
{
    public function handleSettingsRequest(
        Cache $cache,
        Smarty $smarty,
        Language $language,
        &$success,
        &$errors
    ): void;
}