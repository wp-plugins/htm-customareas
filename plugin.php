<?php
/**
 * Plugin Name: HTM Custom Areas
 * Description: A plugin allowing admins to embed content added by others inside existing posts
 * Author: Oliver Burton
 * Author URI: http://www.htmlstudio.co.uk
 * Version: 1.0.0
 * Plugin URI:
 */
 
require_once(ABSPATH . '/wp-admin/includes/plugin.php');
require_once(ABSPATH . WPINC . '/pluggable.php');

$dir = dirname( __FILE__ );
include $dir.'/lib/library/init.php';
$htmCustomAreasInit = new HTMCustomAreasInit($dir);