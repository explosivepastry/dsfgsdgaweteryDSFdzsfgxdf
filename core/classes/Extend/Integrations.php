<?php

namespace NamelessMC\Framework\Extend;

use Illuminate\Container\Container;

class Integrations extends BaseExtender {

    private $integrations = [];

    public function extend(Container $container): void {
        foreach ($this->integrations as $integration) {
            \Integrations::getInstance()->registerIntegration($container->make($integration));
        }
    }

    public function register(string $injector): self {
        $this->integrations[] = $injector;

        return $this;
    }
}