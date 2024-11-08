# Cookie banner

This package allows you to add a cookie banner to your website.
ACF fields for the banner will automatically be registered on the options page

## Installation

1.  Copy the assets to the platform resourecs folder 
``` bash
php artisan vendor:publish --provider="Codetikkers\CodepressExtensions\CookieBanner\CookieBannerServiceProvider" --tag="assets"
```

2. Include the published assets in your css and js files

3. Add the following code to your layout file
``` html
<x-cookie-banner />
```

The cookiebanner will set a HTTPS-cookie with the name `cookieAccepted` and the value `0` or `1` depending on the user's choice. The cookie will expire after 365 days.
