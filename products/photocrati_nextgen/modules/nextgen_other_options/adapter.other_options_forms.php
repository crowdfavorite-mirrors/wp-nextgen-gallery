<?php

class A_Other_Options_Forms extends Mixin
{
	function initialize()
	{
		$forms = array(
			'image_options'     => 'A_Image_Options_Form',
			'thumbnail_options' => 'A_Thumbnail_Options_Form',
			'lightbox_effects'  => 'A_Lightbox_Manager_Form',
			'watermarks'        => 'A_Watermarks_Form'
        );

        if (!is_multisite() || (is_multisite() && C_NextGen_Settings::get_instance()->get('wpmuStyle')))
			$forms['styles'] = 'A_Styles_Form';

        if (is_super_admin() && (!is_multisite() || (is_multisite() && C_NextGen_Settings::get_instance()->get('wpmuRoles'))))
            $forms['roles_and_capabilities'] = 'A_Roles_Form';

        $forms += array(
            'miscellaneous' => 'A_Miscellaneous_Form'
            // NOTE & TODO: the following line must do some authentication to prevent sub-sites admins
            // from affecting others on the network
            // 'reset_and_uninstall' => 'A_Reset_Form'
		);

		$registry = $this->object->get_registry();

		foreach ($forms as $form => $adapter) {
			$registry->add_adapter('I_Form', $adapter, $form);

			$this->object->add_form(
				NEXTGEN_OTHER_OPTIONS_SLUG,
				$form
			);
		}

	}
}
