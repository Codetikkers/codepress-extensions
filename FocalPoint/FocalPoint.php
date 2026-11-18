<?php

namespace Codetikkers\CodepressExtensions\FocalPoint;

class FocalPoint
{
    /**
     * Post meta with the focal point of an image: "x y" in % from the top-left corner, e.g. "62 35".
     */
    public const META_KEY = '_focal_point';

    /**
     * The focal point as [x, y] in %, null when it isn't set (the image is centered).
     */
    public static function get(int $id): ?array
    {
        return static::parse(get_post_meta($id, static::META_KEY, true));
    }

    /**
     * The focal point as a CSS position, e.g. "62% 35%", null when it isn't set.
     */
    public static function position(int $id): ?string
    {
        $point = static::get($id);

        return $point ? "{$point[0]}% {$point[1]}%" : null;
    }

    public static function parse(mixed $value): ?array
    {
        if (!is_string($value) || !preg_match('/^(\d{1,3}) (\d{1,3})$/', trim($value), $match)) {
            return null;
        }

        return [min(100, (int) $match[1]), min(100, (int) $match[2])];
    }
}
