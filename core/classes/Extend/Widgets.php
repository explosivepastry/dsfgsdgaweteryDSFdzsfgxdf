<?php

namespace NamelessMC\Framework\Extend;

use Illuminate\Container\Container;

class Widgets extends BaseExtender {

    private $widgets = [];

    public function extend(Container $container): void {
        /** @var \Pages */
        $pages = $container->get(\Pages::class);

        // Skip initialization if we don't need to display any widgets
        if (!$pages->getActivePage()['widgets'] && (defined('PANEL_PAGE') && !str_contains(PANEL_PAGE, 'widget'))) {
            return;
        }

        /** @var \Widgets */
        $widgets = $container->get(\Widgets::class);

        foreach ($this->widgets as $widget) {
            $widgets->add($container->make($widget));
        }
    }

    public function register(string $widget): self {
        $this->widgets[] = $widget;

        return $this;
    }
}