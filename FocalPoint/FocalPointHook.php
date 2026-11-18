<?php

namespace Codetikkers\CodepressExtensions\FocalPoint;

use Codepress\Hook\Hookable;
use Codepress\Support\Facades\Action;
use Codepress\Support\Facades\Filter;
use WP_Post;

class FocalPointHook extends Hookable
{
    public function register(): void
    {
        $this->addField();
        $this->saveField();
        $this->enqueueAssets();
        $this->addImageAttribute();
    }

    /**
     * "Focuspunt" on images in the media modal and the "Edit media" screen: the current point with a button that opens
     * a dialog to click the point on the photo (resources/assets/focal-point.js).
     */
    protected function addField(): void
    {
        Filter::add('attachment_fields_to_edit', function (array $fields, WP_Post $post) {
            if (!wp_attachment_is_image($post)) {
                return $fields;
            }

            $point = FocalPoint::get($post->ID);
            $status = $point ? "Horizontaal {$point[0]}%, verticaal {$point[1]}%" : 'Midden (standaard)';

            $fields['focal_point'] = [
                'label' => 'Focuspunt',
                'input' => 'html',
                'html' => sprintf(
                    '<div class="focal-point-field" data-image="%s">
                        <input type="hidden" class="focal-point-input" name="attachments[%d][focal_point]" value="%s">
                        <p class="focal-point-status">%s</p>
                        <button type="button" class="button button-small focal-point-open">Instellen</button>
                        <button type="button" class="button-link focal-point-reset"%s>Terug naar midden</button>
                    </div>',
                    esc_url(wp_get_attachment_image_url($post->ID, 'large')),
                    $post->ID,
                    esc_attr($point ? implode(' ', $point) : ''),
                    esc_html($status),
                    $point ? '' : ' hidden'
                ),
                'helps' => 'Het belangrijkste punt van de foto, bijvoorbeeld een gezicht. Wanneer de foto wordt bijgesneden of als achtergrond gebruikt, wordt dat punt in beeld gehouden.',
            ];

            return $fields;
        });
    }

    /**
     * Saved from the media modal (on change, via ajax) and from the "Edit media" screen (on update).
     */
    protected function saveField(): void
    {
        Filter::add('attachment_fields_to_save', function (array $post, array $attachment) {
            if (!array_key_exists('focal_point', $attachment)) {
                return $post;
            }

            $point = FocalPoint::parse($attachment['focal_point']);

            $point
                ? update_post_meta($post['ID'], FocalPoint::META_KEY, implode(' ', $point))
                : delete_post_meta($post['ID'], FocalPoint::META_KEY);

            return $post;
        });
    }

    /**
     * Inline, so the extension works without publishing assets. Small enough to load on every admin page, which also
     * covers media modals opened from fields.
     */
    protected function enqueueAssets(): void
    {
        Action::add('admin_enqueue_scripts', function () {
            wp_register_style('codepress-focal-point', false);
            wp_enqueue_style('codepress-focal-point');
            wp_add_inline_style('codepress-focal-point', file_get_contents(__DIR__ . '/resources/assets/focal-point.css'));

            wp_register_script('codepress-focal-point', false, [], null, true);
            wp_enqueue_script('codepress-focal-point');
            wp_add_inline_script('codepress-focal-point', file_get_contents(__DIR__ . '/resources/assets/focal-point.js'));
        });
    }

    /**
     * Opt-in per image: wp_get_attachment_image($id, $size, false, ['class' => '… object-cover', 'focal' => true]) adds
     * the focal point as object-position. Only useful on images that are cropped with object-fit: cover.
     */
    protected function addImageAttribute(): void
    {
        Filter::add('wp_get_attachment_image_attributes', function (array $attr, WP_Post $attachment) {
            if (!array_key_exists('focal', $attr)) {
                return $attr;
            }

            $enabled = $attr['focal'];
            unset($attr['focal']);

            if ($enabled && $position = FocalPoint::position($attachment->ID)) {
                $attr['style'] = trim(($attr['style'] ?? '') . " object-position: {$position};");
            }

            return $attr;
        });
    }
}
