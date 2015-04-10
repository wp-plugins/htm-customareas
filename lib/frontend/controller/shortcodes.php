<?php

/*
 * Short code functionality
 */

class HTMCustomAreasShortcodes extends HTMCustomAreasController {

    private $_postType;
    private $_assetPath;
    
    function __construct($coreDir) {
        parent::__construct($coreDir, 'frontend');
        
        //Load the options 
        $this->_options = get_option($this->_optionName);
        $this->_postType = 'custom_area';
        
        if(array_key_exists('htmcas_css', $this->_options) && $this->_options['htmcas_css'] == 'yes'){
            $this->_assetPath =  get_option('siteurl') . '/wp-content/plugins/htm_customareas/assets/';

            add_action( 'wp_enqueue_scripts', array($this, 'load_css'));
        }
        
        //Register shorcode actions 
        add_shortcode('HTMCustomAreas', array($this, 'do_content_shortcode'));
    }

    /*
     * Load the CSS
     */
    function load_css(){
        wp_enqueue_style( $this->_classPrefix.'style', $this->_assetPath.'css/frontend.css');
    }
    
    
    /*
     * Results functionality -- draw this from the venusearch controller
     */

    function do_content_shortcode($attrs, $content = null) {

        $ids = array_key_exists('id', $attrs) ? $attrs['id'] : '';

        $data['title'] = is_array($attrs) && array_key_exists('title', $attrs) ? $attrs['title'] : 'yes';
        $data['link'] = is_array($attrs) && array_key_exists('title', $attrs) ? $attrs['title'] : 'yes';
        $data['image'] = is_array($attrs) && array_key_exists('img', $attrs) ? $attrs['img'] : 'thumbnail';
        
        $idsforsearch = array_filter(explode(',', $ids));

        //Retrieve posts 
        if (count($idsforsearch) > 0) {
            //Get the posts
            $data['posts'] = $this->get_posts($idsforsearch);

            //Display the posts 
            $this->load_view('content', $data);
        }
    }

    /*
     * Get the posts by id 
     */

    private function get_posts($ids) {
         global $wpdb;
        $prefix = $wpdb->prefix;
        $posts = array();
        $outids = array();

        //Just validate ids
        foreach ($ids as $id) {
            $outids[] = intval($id);
        }


        if (count($outids) > 0) {
            $searchposts = implode(',', $outids);
            $sql = " 
                SELECT 
                p.ID, 
                p.post_title, 
                p.post_content, 
                p.post_excerpt,   
                p.guid,
                pm2.meta_value as link
                FROM {$prefix}posts AS p
                LEFT JOIN {$prefix}postmeta AS pm2 ON (p.ID = pm2.post_id AND pm2.meta_key='htmlcaslink_url')
                WHERE
                p.ID IN ({$searchposts})
                AND p.post_status = 'publish'
                AND p.post_type = '{$this->_postType}'
                ";                
                $posts = $wpdb->get_results($sql, OBJECT);
        
        }
        return $posts;
    }

}
