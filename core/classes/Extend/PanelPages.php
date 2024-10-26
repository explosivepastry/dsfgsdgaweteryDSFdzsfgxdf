<?php

namespace NamelessMC\Framework\Extend;

use Illuminate\Container\Container;

class PanelPages extends BaseExtender {

    private $pages = [];
    private $dropdownPages = [];

    public function extend(Container $container): void {
        /** @var \Language */
        $moduleLanguage = $container->get("{$this->moduleName}Language");

        /** @var \Pages */
        $pages = $container->get(\Pages::class);

        /** @var \User */
        $user = $container->get(\User::class);

        /** @var \Cache */
        $cache = $container->get(\Cache::class);
        $cache->setCache('panel_sidebar');

        /** @var \Navigation */
        $panelNavigation = $container->get('PanelNavigation');

        if (!empty($this->pages)) {
            $moduleSidebarOrder = array_reduce(array_filter($cache->retrieveAll(), function ($item) {
                return str_ends_with($item, '_order');
            }, ARRAY_FILTER_USE_KEY), function ($carry, $item) {
                return $item > $carry ? $item : $carry;
            }, 0) + 1;
            $panelNavigation->add("{$this->moduleName}_divider", mb_strtoupper($this->moduleDisplayName), 'divider', 'top', null, $moduleSidebarOrder);
    
            $lastSubPageOrder = $moduleSidebarOrder;
    
            foreach ($this->pages as $page) {
                $path = ltrim($page['path'], '/');
                $path = "/panel/{$path}";
    
                $order = $lastSubPageOrder + 0.1;
                $lastSubPageOrder = $order;
    
                $title = $moduleLanguage->get($page['title_translation']);
    
                if ($user->hasPermission($page['permission'])) {
                    $icon = "<i class='nav-icon {$page['icon']}'></i>";
                    $panelNavigation->add($page['name'], $title, \URL::build($path), 'top', null, $order, $icon);
                }
    
                $this->registerInternalPage($pages, $path, $page['handler'], $title);
            }
        }

        if (!empty($this->dropdownPages)) {
            foreach ($this->dropdownPages as $dropdownName => $dropdownPages) {
                foreach ($dropdownPages as $page) {
                    $path = ltrim($page['path'], '/');
                    $path = "/panel/{$path}";

                    $title = $moduleLanguage->get($page['title_translation']);
    
                    if ($user->hasPermission($page['permission'])) {
                        $icon = "<i class='nav-icon {$page['icon']}'></i>";
                        $panelNavigation->addItemToDropdown($dropdownName, $page['name'], $title, \URL::build($path), 'top', null, $icon);
                    }

                    $this->registerInternalPage($pages, $path, $page['handler'], $title);
                }
            }
        }
    }

    public function register(string $path, string $name, string $titleTranslation, string $handler, string $permission, string $icon): PanelPages {
        $this->pages[] = [
            'path' => $path,
            'name' => $name,
            'title_translation' => $titleTranslation,
            'handler' => $handler,
            'permission' => $permission,
            'icon' => $icon,
        ];

        return $this;
    }

    public function registerInDropdown(string $dropdownName, string $path, string $name, string $titleTranslation, string $handler, string $permission, string $icon): PanelPages {
        if (!isset($this->dropdownPages[$dropdownName])) {
            $this->dropdownPages[$dropdownName] = [];
        }

        $this->dropdownPages[$dropdownName][] = [
            'path' => $path,
            'name' => $name,
            'title_translation' => $titleTranslation,
            'handler' => $handler,
            'permission' => $permission,
            'icon' => $icon,
        ];

        return $this;
    }

    private function registerInternalPage(\Pages $pages, string $path, string $handler, string $title): void {
        $pages->add(
            $this->moduleDisplayName,
            $path,
            $handler,
            $title,
            false,
            $this->moduleName,
            true,
        );
    }
}