<?php

/*
 * The Options Page 
 */

class HTMCustomAreasAdmin extends HTMCustomAreasController {

    private $_model;
    private $_defaultOptions;
    private $_formHelper;
    private $_assetPath;
    private $_version = "1.0.0";
    private $_key = "HTMCustomAreasVersion";
    private $_optionFields;
    private $_hiddenPostTypes;
    private $_postTypes;
    private $_options;
    //User Roles
    private $_adminRoles;
    private $_editorRoles;
    private $_userRoles;

    function __construct($coreDir) {
        parent::__construct($coreDir, 'admin');

        //Init the form helper instance
        $this->_formHelper = new HTMCustomAreasFormHelper();

        //List the hidden post types
        $this->_hiddenPostTypes = array('attachment', 'revision', 'nav_menu_item', 'custom_area');

        //Who can edit
        $this->_userRoles = array('Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber');
        $this->_adminRoles = array('Administrator');
        $this->_editorRoles = array('Administrator', 'Editor');


        // Listen for the activate event
        register_activation_hook($coreDir.'/plugin.php', array($this, 'activate'));
        
        // Deactivation plugin
        register_deactivation_hook($coreDir.'/plugin.php', array($this, 'deactivate'));

        
         //Get the post types
        $postTypes = get_post_types();
   
        $this->_postTypes = array();

        //loop over the hidden post types 
        foreach ($postTypes as $key => $value) {
            if (!in_array($key, $this->_hiddenPostTypes)) {
                $this->_postTypes[] = $value;
            }
        }
        
        //Init the option fields 
        $this->_optionFields = $this->get_fields();

        //Load the default options 
        $result = $this->load_default_options();

        //Get the options from the db
        $opts = get_option($this->_optionName);

        //Get the options 
        $this->_options = !empty($opts) ? $opts : $result;
        
        

        //Init the data array
        $this->data = array();

        //Register the site url 
        $this->_assetPath = get_option('siteurl') . '/wp-content/plugins/htm_customareas/assets/';

        //Add the menu
        add_action('admin_menu', array($this, 'add_page'));

        //Register the custom styles 
        add_action('admin_head', array($this, 'register_head'));

        //Register some scripts 
        add_action('admin_enqueue_scripts', array($this, 'register_scripts'));

        //Admin init		
        add_action('admin_init', array($this, 'admin_init'));
    }

    /*
      /* Deploy default options
     */

    public function activate() {
        $default = $this->load_default_options();
        update_option($this->_optionName, $default);
        update_option($this->_key, $this->_version);
    }

    /*
     * Remove the options 
     */

    public function deactivate() {
        delete_option($this->_optionName);
        delete_option($this->_key);
    }

    // White list our options using the Settings API
    public function admin_init() {
        //Get the post types
        $postTypes = get_post_types();
   
        //loop over the hidden post types 
        foreach ($postTypes as $key => $value) {
            if (!in_array($key, $this->_hiddenPostTypes) && !in_array($value, $this->_postTypes)) {
                $this->_postTypes[] = $value;
            }
        }
   
        register_setting($this->_classPrefix . '_options', $this->_optionName);
    }

    // Add entry in the settings menu
    public function add_page() {
       // add_menu_page('HTM Custom Areas Plugin Page', 'Custom Areas Options', 'manage_options', $this->_shortName, array($this, 'do_page'));
        add_submenu_page('edit.php?post_type=custom_area' ,'HTM Custom Area Plugin Page', 'Custom Area Options', 'manage_options', $this->_shortName, array($this, 'do_page'));
    }

    /*
     * Queue the required Scripts
     */

    function register_scripts($hook) {
       
        if ($hook != 'custom_area_page_' . $this->_shortName) {
            return;
        }

        //Queue jquery ui
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('custom-script', $this->_assetPath . 'script/ui-tabs.js', array('jquery'));
    }

    /*
     * On registering the head add the styles 
     */

    function register_head() {
        //Get current screen
        $screen = get_current_screen();

        if (in_array($screen->id, array('custom_area_page_' . $this->_shortName))) {
            //Queue styling 
            $url = $this->_assetPath . 'css/admin.css';
            echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
        }
    }

    public function validate($input) {
        $valid = array();

        foreach ($this->_optionFields as $key => $value) {
            $valid[$key] = array_key_exists($key, $input) ? $input[$key] : '';
            $valid[$key] = $this->validate_item($key, $value, $input);
        }

        return $valid;
    }

// Print the menu page itself
    public function do_page() {

        if (!current_user_can('edit_pages')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        //Was the form submitted??
        $this->data['saved'] = array_key_exists('saved', $_REQUEST) ? true : false;


        //Get the options 
        $this->data['options'] = $this->data['saved'] ? $this->validate($_REQUEST) : $this->_options;


        $this->data['user_types'] = $this->_userRoles;
        $this->data['form_url'] = '';
        $this->data['settings_field'] = settings_fields($this->_optionName);
        $this->data['errors'] = $this->_formHelper->get_errors();




        //Get the post types
        $this->data['types'] = $this->_postTypes;


        $error = false;

        foreach ($this->data['errors'] as $e) {
            if ($e != '') {
                $error = true;
            }
        }

        if (!$error) {
            //Update the options	
            update_option($this->_optionName, $this->data['options']);
        } else {
            $this->data['saved'] = false;
        }


        //Get all the posted data         
        $this->load_view('admin', $this->data);
    }

    /*
     * Function: load_default_options
     * Loads the default plugin options
     */

    private function load_default_options() {
        if (empty($this->_defaultOptions)) {
            $return = array();
            foreach ($this->_optionFields as $key => $value) {
                $return[$key] = $value->get_default();
            }
            $this->_defaultOptions = $return;
        }
        return $this->_defaultOptions;
    }

    /*
     * Validate the item
     */

    private function validate_item($key, $value, $input) {

        switch ($value->get_type()) {
            case 'text':
                return $this->_formHelper->validate_text($key, $input, $this->_defaultOptions[$key], $value->get_validation());
            case 'select':
                return $this->_formHelper->validate_select($key, $input, $this->_defaultOptions[$key], $value->get_options());
            case 'checkbox':
                return $this->_formHelper->validate_checkbox($key, $input, $this->_defaultOptions[$key]);
            case 'checkboxarray':
                return $this->_formHelper->validate_checkboxarray($key, $input, $this->_defaultOptions[$key]);
        }
    }

    /*
     * Get the List of fields
     */

    private function get_fields() {
        if (empty($this->_optionFields)) {
            $this->_optionFields = array(
                'htmcas_title' => new HTMCustomAreasOption('select', 'yes', array(), array('yes', 'no')),
                'htmcas_link' => new HTMCustomAreasOption('select', 'yes', array(), array('yes', 'no')),
                'htmcas_featured' => new HTMCustomAreasOption('select', 'thumb', array(), array('none', 'thumbnail', 'medium', 'large')),
                'htmcas_css' => new HTMCustomAreasOption('select', 'yes', array(), array('yes', 'no')),
                'htmcas_post_type' => new HTMCustomAreasOption('checkboxarray', array('post','page'), array(), $this->_postTypes),
                'htmcas_user_add_shortcode' => new HTMCustomAreasOption('checkboxarray', array('administrator'), array(), $this->_userRoles),
                'htmcas_user_add_customarea' => new HTMCustomAreasOption('checkboxarray', $this->_editorRoles, array(), $this->_userRoles),
            );
        }
        return $this->_optionFields;
    }

}