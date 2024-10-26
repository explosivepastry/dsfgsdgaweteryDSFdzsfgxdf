<?php

namespace NamelessMC\Framework\Extend;

use Illuminate\Container\Container;

class GroupSync extends BaseExtender {

    private $injectors = [];

    public function extend(Container $container): void {
        foreach ($this->injectors as $injector) {
            \GroupSyncManager::getInstance()->registerInjector(new $injector);
        }
    }

    public function register(string $injector): self {
        $this->injectors[] = $injector;

        return $this;
    }
}