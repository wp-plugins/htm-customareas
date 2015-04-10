<?php

class HTMCustomArea extends HTMCustomAreasController {

    protected $_postType;
    private $_nonceField;
    private $_nonceAction;
    private $_validationErrors;
    private $_formHelper;
    private $_widgetName;
    private $_options;
    private $_allowedUsers;
    private $_assetPath;

    function __construct($dir) {
        parent::__construct($dir, 'admin');

        $this->_postType = 'custom_area';
        $this->_nonceField = 'htmcustomarea_nonce_field';
        $this->_nonceAction = $this->_classPrefix . '_savemeta';
        $this->_validationErrors = array();
        $this->_formHelper = new HTMCustomAreasFormHelper();
        
        //Register the site url 
        $this->_assetPath = get_option('siteurl') . '/wp-content/plugins/htm_customareas/assets/';

        //Get the Options  
        $this->_options = get_option($this->_optionName);

        if($this->_options == null){
            return;
        }

        //The widget name 
        $this->_widgetName = 'HTMCustomAreaWidget';

        

        //Can we load the post type?
        if ($this->can_show() == false) {
            return;
        }

        //Add action
        add_action('init', array($this, "register_area"));
        add_action('edit_form_after_title', array($this, "register_meta_boxes"));
        add_action('save_post', array($this, 'save_meta_box'));
        add_action('add_meta_boxes', array($this, "register_shortcode_box"));
        //Register the custom styles 
        add_action('admin_head', array($this, 'register_head'));
    }

    
    /*
    * On registering the head add the styles 
    */
    function register_head() {
        //Get current screen
        $screen = get_current_screen();

        if (in_array($screen->id, array($this->_postType))) {
            //Queue styling 
            $url = $this->_assetPath . 'css/linkinput.css';
            echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
        }
    }
    
    /*
     * Registetr the post type
     */

    function register_area() {

        register_post_type($this->_postType, array(
            'labels' => array(
                'name' => __('Custom Areas'),
                'singular_name' => __('Custom Area')
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'customarea'),
            'supports' => array(
                'title',
                'editor',
                'thumbnail')
        ));
    }

    /*
     * Handle the metadata box 
     */

    function register_meta_boxes() {
        global $post, $wp_meta_boxes;

        //Ensure we are in the correct location
        if (empty($post) || $this->_postType !== get_post_type($GLOBALS['post']))
            return;

        //Can we get the metadata?
        if (!$content = get_post_meta($post->ID, 'htmlcaslink_url', TRUE))
            $content = '';

        //Set the link 
        $data['link_url'] = $content;

        //Set the nonce field
        $data['nonce_field'] = wp_nonce_field($this->_nonceAction, $this->_nonceField);

        //Load the template
        $this->load_view('linkmeta', $data);
    }

    /*
     * A box to show the shortcode for use in output 
     */

    public function register_shortcode_box() {
        global $post;
        //Ensure we are in the correct location
        if (empty($post) || $this->_postType !== get_post_type($GLOBALS['post']))
            return;

        //Is this an admin user?
        if (current_user_can('manage_options')) {
            add_meta_box(
                    'htmlca_shortode_box', 'Post Shortcode', array($this, 'shortcode_box_content'), $this->_postType, 'side', 'high'
            );
        }
    }

    /*
     * Output the shortcode
     */

    public function shortcode_box_content($post) {
        $data['id'] = $post->ID;
        $this->load_view('shortcodebox', $data);
    }

    /*
     * Save the metadata
     */

    function save_meta_box($post_id) {

        //Save things ! Only allow absolute url
        // if this fails, check_admin_referer() will automatically print a "failed" page and die.
        if ($this->_postType !== get_post_type($GLOBALS['post'])) {
            return;
        }

        if (!empty($_POST) && check_admin_referer($this->_nonceAction, $this->_nonceField)) {

            //user has access??
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }


            //Validate the form 
            $link_url = $this->validate_meta_form();

            //Get any errors		 
            $error = $this->_formHelper->has_an_error();

            //It went wrong
            if ($error) {
                return;
            }

            //Save the metadata
            update_post_meta($post_id, 'htmlcaslink_url', $link_url);
        }
    }

    /*
     * Custom form validation function 
     */

    private function validate_meta_form() {

        if ($this->_postType !== get_post_type($GLOBALS['post'])) {
            return;
        }



        if (!empty($_POST) && check_admin_referer($this->_nonceAction, $this->_nonceField)) {

            //Validate
            $link_url = $this->_formHelper->validate_text('htmlcaslink_url', $_POST, array('htmlcaslink_url' => ''), array('is_link' => ''));

            //Get any errors
            $this->_validationErrors = $this->_formHelper->get_errors();

            return $link_url;
        }
        return '';
    }

    // Register and load the widget
    function load_widget() {
        register_widget($this->_widgetName);
    }

    /*
     * Can we load the class for this user
     */

    private function can_show() {
        //Get the allowed users
        $allowedUsers = array_key_exists('htmcas_user_add_customarea', $this->_options) ? $this->_options['htmcas_user_add_customarea'] : array();

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