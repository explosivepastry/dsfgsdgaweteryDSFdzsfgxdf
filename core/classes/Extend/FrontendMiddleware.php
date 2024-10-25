<?php

namespace NamelessMC\Framework\Extend;
use Illuminate\Container\Container;

class FrontendMiddleware extends BaseExtender
{
    private array $middlewares = [];

    public function extend(Container $container): void
    {
        if ($container->has('FrontendMiddleware')) {
            $middlewares = $container->get('FrontendMiddleware');
        } else {
            $middlewares = $container->instance('FrontendMiddleware', []);
        }

        $middlewares = array_merge($middlewares, $this->middlewares);

        $container->instance('FrontendMiddleware', $middlewares);
    }

    public function register(string $middleware): self
    {
        $this->middlewares[] = $middleware;

        return $this;
    }
}