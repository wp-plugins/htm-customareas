<?php

class HTMCustomAreasAjaxController extends HTMCustomAreasController {

    protected $_nonceAction;
    protected $_assetPath;
    protected $_action;
    protected $_ajaxScript;
    protected $_nonceValue;

    public function __construct($pluginDir, $dir) {
        parent::__construct($pluginDir, $dir);

        //Register the site url 
        $this->_assetPath = get_option('siteurl') . '/wp-content/plugins/' . $this->_pluginFolderName . '/assets/';

        //Set Nonce field
        $this->_nonceField = $this->_classPrefix . '_ajax';
        $this->_nonceAction = $this->_classPrefix . '_ajax_lookup';
        $this->_action = $this->_classPrefix . '_do_ajax_request';
        $this->_ajaxScript = $this->_classPrefix . '_ajax.js';

        $this->_nonceValue = wp_create_nonce($this->_nonceField);

        //Register ajax actions 
        add_action('wp_ajax_' . $this->_action, array($this, 'do_ajax_request'));
        add_action('wp_ajax_nopriv' . $this->_action, array($this, 'do_invalid_ajax_request'));
    }

    /*
     * Handle the ajax request
     */

    public function do_ajax_request() {
        
        if (!empty($_REQUEST) && array_key_exists($this->_nonceField, $_REQUEST) && wp_verify_nonce($_REQUEST[$this->_nonceField], $this->_nonceField)) {
            $retort = $this->get_ajax_response();
        } else {
            // Build the response if an error occurred
            $retort = $this->get_ajax_error_response();
        }

        wp_send_json($retort);

        // Always exit when doing Ajax
        exit();
    }

    /*
     * Function adds a scrt id admin 
     */

    public function register_scripts($hook) {

        //Queue jquery ui
        wp_enqueue_script($this->_classPrefix . '_ajax_params', $this->_assetPath . 'script/' . $this->_ajaxScript, array('jquery'));

        $params = array(
            // Get the url to the admin-ajax.php file using admin_url()
            'ajaxurl' => admin_url('admin-ajax.php'),
            'action' => $this->_action
        );

        // Print the script to our page
        wp_localize_script($this->_classPrefix . '_ajax_params', $this->_classPrefix . '_params', $params);
    }

    /*
     * Handle the invalid ajax request
     */

    public function do_invalid_ajax_request() {
        // Build the response if an error occurred
        $retort = $this->get_ajax_error_response();
        wp_send_json($retort);
        exit();
    }

    /*
     * Get  blank response 
     */

    protected function get_ajax_response($result = array()) {
        return $this->get_ajax_message($result, 'success');
    }

    /*
     * Get the error response array
     */

    protected function get_ajax_error_response() {
        return $this->get_ajax_message('');
    }

    /*
     * Get the response array
     */

    protected function get_ajax_message($message, $error = 'error') {
        // Build the response if successful
        return array(
            'data' => $error,
            'supplemental' => array(
                'message' => $message,
            ),
        );
    }

}