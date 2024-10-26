<?php

namespace NamelessMC\Framework\Extend;

use Illuminate\Container\Container;

class FrontendPages extends BaseExtender {

    private $pages = [];

    public function extend(Container $container): void {
        /** @var \Language */
        $moduleLanguage = $container->get("{$this->moduleName}Language");

        /** @var \Pages */
        $pages = $container->get(\Pages::class);

        /** @var \Cache */
        $cache = $container->get(\Cache::class);

        /** @var \Navigation */
        $frontendNavigation = $container->get('FrontendNavigation');

        foreach ($this->pages as $page) {
            $path = $page['path'];
            $name = $page['name'];
            $title = $moduleLanguage->get($page['title_translation']);

            $cache->setCache('navbar_order');
            $order = $cache->fetch("{$name}_order", fn () => 5);
    
            $cache->setCache('navbar_icons');
            $icon = $cache->fetch("{$name}_icon", fn () => '');

            $cache->setCache('nav_location');
            $location = $cache->fetch("{$name}_location", fn () => 1);

            switch ($location) {
                case 1:
                    // Navbar
                    $frontendNavigation->add($name, $title, \URL::build($path), 'top', null, $order, $icon);
                    break;
                case 2:
                    // "More" dropdown
                    $frontendNavigation->addItemToDropdown('more_dropdown', $name, $title, \URL::build($path), 'top', null, $icon, $order);
                    break;
                case 3:
                    // Footer
                    $frontendNavigation->add($name, $title, \URL::build($path), 'footer', null, $order, $icon);
                    break;
            }    

            $pages->add(
                $this->moduleDisplayName,
                $path,
                $page['handler'],
                $title,
                $page['allowWidgets'],
                $this->moduleName,
                true,
                $name,
            );
        }
    }

    public function register(string $path, string $name, string $titleTranslation, string $handler, bool $allowWidgets): FrontendPages {
        $this->pages[] = [
            'path' => $path,
            'name' => $name,
            'title_translation' => $titleTranslation,
            'handler' => $handler,
            'allowWidgets' => $allowWidgets
        ];

        return $this;
    }
}