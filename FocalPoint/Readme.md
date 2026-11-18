# Focal point

Lets editors set the focal point of an image (e.g. a face) in the media library, so blocks that crop the image with
`object-fit: cover` keep that point in view.

The media modal and the "Edit media" screen get a "Focuspunt" field with an "Instellen" button. It opens a dialog with
the photo: click or drag on it (or use the arrow keys) to place the point. The point is stored as post meta `_focal_point`
("x y" in % from the top-left corner), no ACF needed.

## Installation

Add the hook to the Hookables in `config/app.php`:
``` php
'hooks' => [
    ...
    Codetikkers\CodepressExtensions\FocalPoint\FocalPointHook::class,
    ...
]
```

## Usage

It's opt-in per image. Pass `'focal' => true` to `wp_get_attachment_image()` on an image that is cropped, and the focal
point is added as `object-position` (an inline style, since the value differs per image):
``` php
{!! wp_get_attachment_image($id, 'large', false, ['class' => 'size-full object-cover', 'focal' => true]) !!}
```

Images without the option, or without a focal point, are left alone. For other uses (e.g. a background image),
`FocalPoint::position($id)` returns the CSS position (`"62% 35%"`) and `FocalPoint::get($id)` the point as `[x, y]`;
both return null when no point is set.
