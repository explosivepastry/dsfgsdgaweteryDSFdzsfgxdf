<?php

namespace NamelessMC\Framework\Extend;

use Illuminate\Container\Container as IlluminateContainer;

class Container extends BaseExtender {

    private $singletons = [];

    public function extend(IlluminateContainer $container): void {
        foreach ($this->singletons as $class) {
            $container->singleton($class);
        }
    }

    public function singleton(string $class): Container {
        $this->singletons[] = $class;

        return $this;
    }
}