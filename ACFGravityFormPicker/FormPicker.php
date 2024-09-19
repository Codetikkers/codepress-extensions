<?php

namespace Codetikkers\CodepressExtensions\ACFGravityFormPicker;

/**
 * ACF Field: Gravity Form Picker.
 *
 * @see https://www.advancedcustomfields.com/resources/creating-a-new-field-type/
 */
class FormPicker extends \acf_field {
	/**
	 * Controls field type visibilty in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environment values relating to the theme or plugin.
	 *
	 * @var array $env Plugin or theme context such as 'url' and 'version'.
	 */
	private array $env;

	/**
	 * Constructor.
	 */
	public function __construct() {
		/**
		 * Field type reference used in PHP and JS code.
		 *
		 * No spaces. Underscores allowed.
		 */
		$this->name = 'form_picker';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = 'Form Picker';

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'relational'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME

		/**
		 * Field type Description.
		 *
		 * For field descriptions. May contain spaces.
		 */
		$this->description = 'Display a dropdown with the created Gravity forms';

		/**
		 * Defaults for your custom user-facing settings for this field type.
		 */
		$this->defaults = [];

		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("form_picker", "error");
		 * ```
		 */
		$this->l10n = array(
			'error'	=> 'Error! Please select a form',
		);

		$this->env = array(
			'url'     => site_url( str_replace( ABSPATH, '', __DIR__ ) ), // URL to the acf-FIELD-NAME directory.
			'version' => '1.0', // Replace this with your theme or plugin version constant.
		);

		/**
		 * Field type preview image.
		 *
		 * A preview image for the field type in the picker modal.
		 */
		$this->preview_image = $this->env['url'] . '/assets/images/field-preview-custom.png';

		parent::__construct();
	}

	public function render_field( array $field ): void
    {
        $forms = \GFAPI::get_forms();
        $choices = [];
        foreach ($forms as $form) {
            $choices[$form['id']] = $form['title'];
        }

		echo '<select class="settings-form-picker" name="' . esc_attr( $field['name'] ) . '">';
        echo '<option value=""' . (!$field['value'] ? "selected" :  '') . '>Selecteer een formulier</option>';
        foreach ($choices as $id => $title) {
            echo '<option ' . ($field['value'] == $id ? 'selected' : '')  . ' value="' . $id . '">' . $title . '</option>';
        }
        echo '</select>';
	}
}