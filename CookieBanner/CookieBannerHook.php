<?php

namespace Codetikkers\CodepressExtensions\CookieBanner;

use Codepress\Hook\Hookable;
use Codepress\Support\Facades\Filter;

class CookieBannerHook extends Hookable
{
    public function register(): void
    {
        Filter::add('acf/settings/load_json', function(array $directories) {
            $directories[] = __DIR__ . '/acf-json';
            return $directories;
        });
    }
}