<?php

namespace NamelessMC\Framework\Extend;

use Illuminate\Container\Container;

class Endpoints extends BaseExtender {

    private $endpoints = [];

    public function extend(Container $container): void {
        /** @var \Endpoints */
        $endpoints = $container->get(\Endpoints::class);

        foreach ($this->endpoints as $endpoint) {
            $endpoints->loadEndpoint($endpoint);
        }
    }

    public function register(string $injector): self {
        $this->endpoints[] = $injector;

        return $this;
    }
}