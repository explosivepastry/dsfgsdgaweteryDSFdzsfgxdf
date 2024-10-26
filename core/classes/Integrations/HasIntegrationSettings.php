<?php

interface HasIntegrationSettings
{
    public function handleSettingsRequest(
        Smarty $smarty,
        Language $language,
        array &$errors
    ): void;
}