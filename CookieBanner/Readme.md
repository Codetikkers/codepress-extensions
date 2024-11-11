# Cookie banner

This package allows you to add a cookie banner to your website.
ACF fields for the banner will automatically be registered on the options page

## Installation

1. Add the cookie banner Service provider to the `config/app.php` file
``` php
'providers' => [
    ...
    Codetikkers\CodepressExtensions\CookieBanner\CookieBannerServiceProvider::class,
    ...
]
```

2. Add the ACF fields hook to the Hookables in the `config/app.php` file
``` php
'hooks' => [
    ...
    odetikkers\CodepressExtensions\CookieBanner\CookieBannerHook::class,
    ...
]
```

3. Copy the assets to the platform resourecs folder 
``` bash
php artisan vendor:publish --provider="Codetikkers\CodepressExtensions\CookieBanner\CookieBannerServiceProvider" --tag="assets"
```

4. Include the published assets in your css and js files

5. Add the following code to your layout file
``` html
<x-cookie-banner />
```

The cookiebanner will set a HTTPS-cookie with the name `cookieAccepted` and the value `0` or `1` depending on the user's choice. The cookie will expire after 365 days.
