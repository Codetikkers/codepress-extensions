# Codepress WordPress Extensions

This package is a collection of Codepress WordPress extensions, which can be registered with as Service Providers in a Codepress WordPress application.

## Extensions

## ACF Gravity Form Picker
Creates an ACF (Advanced Custom Fields) field type that allows you to pick a Gravity Forms form.
Checks if the Gravity Forms plugin and ACF is installed and activated before registering the field type.

## Focal point
Lets editors set the focal point of an image in the media library; blocks opt in with `'focal' => true` on
`wp_get_attachment_image()` to keep that point in view when the image is cropped. See `FocalPoint/Readme.md`.


## Extensions Ideas

- Shortpixel image optimizer: Optimize images via the shortpixel API on upload