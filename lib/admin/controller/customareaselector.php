<?php

/*
 * A class representing the article selector on pages and posts
 */

class HTMCustomAreasSelector extends HTMCustomAreasAjaxController {

    private $_tinyMCEName;
    private $_postType;
    private $_options;
    private $_postTypes;

    function __construct($dir) {

        parent::__construct($dir, 'admin');

        //Init tiny mce
        $this->_tinyMCEName = $this->_classPrefix . '_tinymce';
        $this->_ajaxScript = 'ajaxselector.js';
        $this->_postType = 'custom_area';

        //Load the options 
        $this->_options = get_option($this->_optionName);
        
        if($this->_options == null){
            return;
        }

        
        //Load the valid post types
        $this->_postTypes = array_key_exists('htmcas_post_type', $this->_options) ? $this->_options['htmcas_post_type'] : array();

        //Can we load the post type?
        if ($this->can_show() == false) {
            return;
        }

        //Register the custom styles 
        add_action('admin_head', array($this, 'register_head'));

        //Register some scripts 
        add_action('admin_enqueue_scripts', array($this, 'register_scripts'));

        //Add item after editor	  	
        add_action('edit_form_after_editor', array($this, 'custom_areas_dialog'));
    }

    /*
     * On registering the head add the styles 
     */

    function register_head() {
        //Get current screen
        $screen = get_current_screen();

        if (in_array($screen->id, $this->_postTypes)) {
            //Queue styling 
            $url = $this->_assetPath . 'css/areaselector.css';
            echo "<link rel='stylesheet' type='text/css' href='$url' />\n";

            //Register new button 
            add_filter('mce_external_plugins', array($this, 'register_tiny_mce_jscript'));
            add_filter('mce_buttons', array($this, 'register_plugin_buttons'));
        }
    }

    /*
     * Register the button for the tiny mce 
     */

    function register_plugin_buttons($buttons) {
        //Add my new button
        array_push($buttons, $this->_tinyMCEName);
        return $buttons;
    }

    /*
     * Register the Script 
     */

    function register_tiny_mce_jscript($plugin_array) {
        $plugin_array[$this->_tinyMCEName] = get_option('siteurl') . '/wp-content/plugins/' . $this->_pluginFolderName . '/assets/script/tinymce-plugin.js';
        return $plugin_array;
    }

    /*
     * Do shortcode box 
     */

    public function custom_areas_dialog() {
        global $post;

        //Get current screen
        $screen = get_current_screen();

        //Validate that we can add this
        if (in_array($screen->id, $this->_postTypes)) {

            $data = array();
            // Create a nonce for this action
            $data['nonce'] = array('name'=>$this->_nonceField, 'value'=>$this->_nonceValue);
            $data['defaults'] = $this->_options;
            $data['posts'] = $this->lookup_posts();
            echo $this->load_view('ajaxlookup', $data);
        }
    }

    /*
     * Function adds a scrt id admin 
     */

    public function register_scripts($hook) {

        if (($hook != 'post.php' && $hook != 'post-new.php') || !current_user_can('manage_options')) {
            return;
        }

        //Queue jquery ui
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style("wp-jquery-ui-dialog");

        parent::register_scripts($hook);
    }

    /*
     * Return a list of posts
     */

    private function lookup_posts($title = null) {
        global $wpdb;
        $prefix = $wpdb->prefix;

        $lowertitle = strtolower($title);

        $like = $lowertitle != '' ? "AND LOWER(p.post_title) LIKE LOWER('%{$lowertitle}%')" : '';

        $sql = " 
        SELECT 
        p.ID, 
       	p.post_title, 
        p.post_content,        
        pm2.meta_value as link
       	FROM {$prefix}posts AS p
        LEFT JOIN {$prefix}postmeta AS pm2 ON (p.ID = pm2.post_id AND pm2.meta_key='htmlcaslink_url')
        WHERE p.post_type = '{$this->_postType}'
        AND p.post_status = 'publish'
      	{$like}
        ORDER BY p.post_title ASC";
        $results = $wpdb->get_results($sql, OBJECT);

        return $results;
    }

    /*
     * Get the response - override default 
     */

    public function get_ajax_response($result = array()) {

        //Get the title
        $title = array_key_exists('input', $_REQUEST) && $_REQUEST['input'] != '' ? sanitize_text_field($_REQUEST['input']) : '';

        //Get the posts
        $result_arr = $this->lookup_posts($title);
        return parent::get_ajax_response($result_arr);
    }

    /*
     * Can we load the class for this user
     */

    private function can_show() {
        //Get the allowed users
        $allowedUsers = array_key_exists('htmcas_user_add_shortcode', $this->_options) ? $this->_options['htmcas_user_add_shortcode'] : array();

        //Get the current user type 
        $current_user = wp_get_current_user();

        if (!($current_user instanceof WP_User))
            return false;

        $roles = $current_user->roles;

        //Does the user have the correct role ?
        for ($i = 0; $i < count($roles); $i++) {
            if (in_array(ucfirst($roles[$i]), $allowedUsers)) {
                return true;
            }
        }

        return false;
    }
}