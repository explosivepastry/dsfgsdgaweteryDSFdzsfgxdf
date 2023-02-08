<?php

$language_files = glob('modules/*/language/*.json');
$language_files = array_merge($language_files, glob('custom/languages/*.json'));

foreach ($language_files as $language_file) {
    $language = json_decode(file_get_contents($language_file), true);

    foreach ($language as $key => $value) {
        if (empty($value)) {
            unset($language[$key]);
        }
    }

    file_put_contents($language_file, json_encode($language, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
