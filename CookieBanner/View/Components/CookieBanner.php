<?php

namespace Codetikkers\CodepressExtensions\CookieBanner\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CookieBanner extends Component
{
    public function __construct() {}

    public function render(): View|string
    {
        $text = get_field('cookie-banner-text', 'option');
        return view('cookie-banner::components.cookie-banner', ['text' => $text]);
    }
}
