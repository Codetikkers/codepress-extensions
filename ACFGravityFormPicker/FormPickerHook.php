<?php

namespace Codetikkers\CodepressExtensions\ACFGravityFormPicker;

use Codepress\Hook\Hookable;
use Codepress\Support\Facades\Action;

class FormPickerHook extends Hookable
{
    public function register(): void
    {
        Action::add('init', function() {
            $GravityAndAcfLoaded = class_exists('GFCommon') && class_exists('ACF');
            if ($GravityAndAcfLoaded) {
                acf_register_field_type(FormPicker::class );
            }
        });
    }
}