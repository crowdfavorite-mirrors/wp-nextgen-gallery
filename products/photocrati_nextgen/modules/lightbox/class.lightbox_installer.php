<?php

class C_Lightbox_Installer
{
    function __construct()
    {
        $this->registry = C_Component_Registry::get_instance();
        $this->router   = $this->registry->get_utility('I_Router');
        $this->mapper   = $this->registry->get_utility('I_Lightbox_Library_Mapper');
    }


    function set_attr(&$obj, $key, $val, $force=FALSE)
    {
        if (!isset($obj->$key) OR $force)
            $obj->$key = $val;
    }

    /**
     * Installs a lightbox library
     * @param string $name
     * @param string $code
     * @param array $stylesheet_paths
     * @param array $script_paths
     * @param array $values
     */
    function install_lightbox($name, $title, $code, $stylesheet_paths=array(), $script_paths=array(), $values=array(), $update=FALSE)
    {
        // Try to find the existing lightbox. If we can't find it, we'll create
        $lightbox		= $this->mapper->find_by_name($name);
        if (!$lightbox)
            $lightbox = new stdClass;

        // Set properties
        $lightbox->name	= $name;
        $this->set_attr($lightbox, 'title', $title, TRUE);
        $this->set_attr($lightbox, 'code', $code);
        $this->set_attr($lightbox, 'values', $values);

        // Overrides styles and scripts if localhost is used
        if (isset($lightbox->styles) && strpos($lightbox->styles, 'localhost') !== FALSE)
            $this->set_attr($lightbox, 'styles', implode("\n", $stylesheet_paths), TRUE);
        else
            $this->set_attr($lightbox, 'styles', implode("\n", $stylesheet_paths));

        if (isset($lightbox->scripts) && strpos($lightbox->scripts, 'localhost') !== FALSE)
            $this->set_attr($lightbox, 'scripts', implode("\n", $script_paths), TRUE);
        else
            $this->set_attr($lightbox, 'scripts', implode("\n", $script_paths));

        // Save the lightbox
        // Note: the validation method will convert absolute urls to relative urls if needed
        $this->mapper->save($lightbox);
    }

    /**
     * Uninstalls an existing lightbox
     * @param string $name
     */
    function uninstall_lightbox($name)
    {
        if (($lightbox = $this->mapper->find_by_name($name))) {
            $this->mapper->destroy($lightbox);
        }
    }

    /**
     * Installs all of the lightbox provided by this module
     */
    function install()
    {
        // Install "None" option
        $this->install_lightbox(
            'none',
            'No lightbox',
            '',
            array(),
            array()
        );

        $this->install_lightbox(
            'lightbox',
            'Lightbox',
            "class='ngg_lightbox'",
            array('photocrati-lightbox#jquery.lightbox/jquery.lightbox-0.5.css'),
            array(
                'photocrati-lightbox#jquery.lightbox/jquery.lightbox-0.5.min.js',
                'photocrati-lightbox#jquery.lightbox/nextgen_lightbox_init.js'
            ),
            array(
                'nextgen_lightbox_loading_img_url' => 'photocrati-lightbox#jquery.lightbox/lightbox-ico-loading.gif',
                'nextgen_lightbox_close_btn_url'   => 'photocrati-lightbox#jquery.lightbox/lightbox-btn-close.gif',
                'nextgen_lightbox_btn_prev_url'    => 'photocrati-lightbox#jquery.lightbox/lightbox-btn-prev.gif',
                'nextgen_lightbox_btn_next_url'    => 'photocrati-lightbox#jquery.lightbox/lightbox-btn-next.gif',
                'nextgen_lightbox_blank_img_url'   => 'photocrati-lightbox#jquery.lightbox/lightbox-blank.gif'
            )
        );

        // Install Fancybox 1.3.4
        $this->install_lightbox(
            'fancybox',
            'Fancybox',
            'class="ngg-fancybox" rel="%GALLERY_NAME%"',
            array('photocrati-lightbox#fancybox/jquery.fancybox-1.3.4.css'),
            array(
                'photocrati-lightbox#fancybox/jquery.easing-1.3.pack.js',
                'photocrati-lightbox#fancybox/jquery.fancybox-1.3.4.pack.js',
                'photocrati-lightbox#fancybox/nextgen_fancybox_init.js'
            )
        );

        // Install highslide
        $this->install_lightbox(
            'highslide',
            'Highslide',
            'class="highslide" onclick="return hs.expand(this, {slideshowGroup: ' . "'%GALLERY_NAME%'" . '});"',
            array('photocrati-lightbox#highslide/highslide.css'),
            array('photocrati-lightbox#highslide/highslide-full.packed.js',
                'photocrati-lightbox#highslide/nextgen_highslide_init.js'),
            array('nextgen_highslide_graphics_dir' => 'photocrati-lightbox#highslide/graphics')
        );

        // Install Shutter
        $this->install_lightbox(
            'shutter',
            'Shutter',
            'class="shutterset_%GALLERY_NAME%"',
            array('photocrati-lightbox#shutter/shutter.css'),
            array('photocrati-lightbox#shutter/shutter.js',
                'photocrati-lightbox#shutter/nextgen_shutter.js'),
            array(
                'msgLoading'	=>	'L O A D I N G',
                'msgClose'		=>	'Click to Close',
            )
        );

        // Install Shutter Reloaded
        $this->install_lightbox(
            'shutter2',
            'Shutter 2',
            'class="shutterset_%GALLERY_NAME%"',
            array('photocrati-lightbox#shutter_reloaded/shutter.css'),
            array('photocrati-lightbox#shutter_reloaded/shutter.js',
                'photocrati-lightbox#shutter_reloaded/nextgen_shutter_reloaded.js')
        );

        // Install Thickbox
        $this->install_lightbox(
            'thickbox',
            'Thickbox',
            "class='thickbox' rel='%GALLERY_NAME%'",
            array('wordpress#thickbox'),
            array('photocrati-lightbox#thickbox/nextgen_thickbox_init.js',
                'wordpress#thickbox')
        );
    }

    /**
     * Uninstalls all lightboxes
     */
    function uninstall($hard = FALSE)
    {
        $this->mapper->delete()->run_query();
    }
}
