<?php

class HTMCustomAreasController {

    protected $_viewdir;
    protected $_root;
    protected $_classPrefix = 'HTMCustomAreas';
    protected $_shortName = 'htmcas-options';
    protected $_optionName = 'HTMCAS-options';
    protected $_pluginPath;
    protected $_pluginFolderName = 'htm_customareas';

    public function __construct($pluginDir, $dir) {
        $this->_pluginPath = $pluginDir;
        $this->_root = $this->_pluginPath . '/lib/';
        $this->_viewdir = $dir . '/';
    }

    protected function load_model($file) {
        //Try and include the file 
        $className = $this->classPrefix . ucfirst($file) . '_model';
        include($this->_root . '/' . $this->_viewdir . 'model/' . $file . '.php');
        return new $className();
    }

    protected function load_view($file, $data) {
        extract($data);
        ob_start();
        include($this->_root . '/' . $this->_viewdir . 'templates/' . $file . '.php');
        ob_end_flush();
    }

    protected function get_current_post_type() {
        global $post, $typenow, $current_screen;
        if ($post && $post->post_type) {
            return $post->post_type;
        } elseif ($typenow) {
            return $typenow;
        } elseif ($current_screen && $current_screen->post_type) {
            return $current_screen->post_type;
        } elseif (isset($_REQUEST['post_type'])) {
            return sanitize_key($_REQUEST['post_type']);
        } elseif (isset($_GET['post'])) {
            $thispost = get_post($_GET['post']);
            return $thispost->post_type;
        } else {
            return null;
        }
    }

}