<?php

namespace App\Services\PageManager;

use App\Models\Page;
use Illuminate\Support\Facades\Route;

class PageManager
{
    private $usedRoutes = null;
    private $staticPages = null;
    private $activeStaticPages = null;
    private $homePage = null;
    private $staticPagesCollection = null;

    public function getHomePage()
    {
        if (!$this->homePage) $this->homePage = settings('home_page', 'home');

        return $this->homePage;
    }

    public function getUsedRoutes()
    {
        if ($this->usedRoutes === null) $this->usedRoutes =
            collect(Route::getRoutes())->map(function ($route) {
                return explode('/', $route->uri())[0];
            })->filter(function ($value) {
                return !(empty($value) || preg_match('/[\{]/', $value));
            })->unique()->values()->toArray();

        return $this->usedRoutes;
    }

    public function inUsedRoutes($name)
    {
        return in_array($name, $this->getUsedRoutes());
    }

    public static function getContactPage()
    {
        return Page::where('static', 'contact')->first();
    }

    private function setStaticPages()
    {
        $staticPages = Page::getStaticPages();
        $this->staticPagesCollection = $staticPages;
        $this->staticPages = $staticPages->mapWithKeys(function ($item) {
            return [$item->static => $item->url];
        })->toArray();
        $this->activeStaticPages = $staticPages->filter(function ($item) {
            return $item->active == 1;
        })->map(function ($item) {
            return $item->static;
        })->toArray();
    }

    public function getPages()
    {
        if ($this->staticPagesCollection === null) $this->setStaticPages();

        return $this->staticPagesCollection;
    }

    public function getPage($static)
    {
        return $this->getPages()->where('static', $static)->first();
    }

    public function getStaticPages()
    {
        if ($this->staticPages === null) $this->setStaticPages();

        return $this->staticPages;
    }

    public function getActiveStaticPages()
    {
        if ($this->activeStaticPages === null) $this->setStaticPages();

        return $this->activeStaticPages;
    }

    public function isActive($page)
    {
        return in_array($page, $this->getActiveStaticPages());
    }

    public function action($value)
    {
        $staticPages = $this->getStaticPages();

        return $staticPages[$value] ?? $value;
    }
}
