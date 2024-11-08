<?php

namespace Codetikkers\CodepressExtensions\CookieBanner;

use Codetikkers\CodepressExtensions\CookieBanner\View\Components\CookieBanner;
use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class CookieBannerServiceProvider extends ServiceProvider
{
    /**
     * @throws EntryNotFoundException
     */
    public function register(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'cookie-banner');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/resources/assets/js' => resource_path('assets/js/extensions/cookie-banner'),
                __DIR__.'/resources/assets/css' => resource_path('assets/sass/extensions/cookie-banner'),
            ], 'assets');
        }

        $this->addOptionsPage();
    }
    public function boot(): void
    {
         Blade::component('cookie-banner', CookieBanner::class);
    }

    /**
     * @throws EntryNotFoundException
     */
    private function addOptionsPage(): void
    {
        $currentPages = config('acf.options.subpages');
        if (!is_array($currentPages)) {
            $currentPages = [];
        }
        $currentPages[] = [
            'page_title' => 'Website - cookiebanner',
            'menu_title' => 'Cookiebanner',
            'parent_slug' => 'website-options',
            'position' => 70,
        ];
        Config::set('acf.options.subpages', $currentPages);
    }
}