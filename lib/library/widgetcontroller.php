<?php
class HTMCustomAreasWidgetController extends WP_Widget{  
   	protected $_viewdir;
    protected $_root;
	protected $_pluginPath;
	protected $_widgetName;
	protected $_widgetTitle;
    protected $_widgetDescription;
	protected $_widgetDomain;
	protected $_widgetContent = '';
	
    public function __construct($pluginDir, $dir, $widgetName, $widgetTitle, $widgetDescription){
    	
		$this->_pluginPath = $pluginDir;        
        $this->_root = $this->_pluginPath.'/lib/';
        $this->_viewdir = $dir.'/';
		    	
		$this->_widgetName = $widgetName; 
		$this->_widgetTitle = $widgetTitle; 
		$this->_widgetDescription = $widgetDescription; 
		$this->_widgetDomain = $this->_widgetName.'_domain';
		
		parent::__construct(
			$this->_widgetName,
			__($this->_widgetTitle, $this->_widgetDomain  ),
			array( 'description' => __( $this->_widgetDescription, $this->_widgetDomain ), )
		);
	}    

	public function widget( $args, $instance ) {
	
		$title = apply_filters( 'widget_title', $instance['title'] );
	
		echo $args['before_widget'];
	
		if ( ! empty( $title ) )	
			echo $args['before_title'] . $title . $args['after_title'];
			
			// This is where you run the code and display the output
			echo __( $this->_widgetContent, $this->_widgetDomain);			
			echo $args['after_widget'];	
	}
	
	public function form( $instance ) {	
		if ( isset( $instance[ 'title' ] ) ) {		
			$title = $instance[ 'title' ];		
		}		
		else {
			$title = __( 'New title', $this->_widgetDomain );		
		}		
		//No output	
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
	
	protected function load_view($file, $data){
        extract($data);
        ob_start();
        include($this->_root.'/'.$this->_viewdir.'templates/'.$file.'.php');
        ob_end_flush();        
    }	

	/// Register and load the widget
	/*function wpb_load_widget() {
	    register_widget( 'wpb_widget' );
	
	}
		add_action( 'widgets_init', 'wpb_load_widget' );
	*/
	
}