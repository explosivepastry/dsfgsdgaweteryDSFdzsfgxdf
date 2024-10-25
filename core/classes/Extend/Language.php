<?php

namespace NamelessMC\Framework\Extend;
use Illuminate\Container\Container;

class Language extends BaseExtender {

    private string $path;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function extend(Container $container): void {
        $containerKey = "{$this->moduleName}Language";

        $container->bind($containerKey, fn () => new \Language($this->path));
    }
}