<?php
class HTMCustomAreasOption{

	private $_default; 
	private $_validation;
	private $_type;	
	private $_possopts;
	
	public function __construct($type, $default, $validation = array(), $possopts = array()){
	   $this->_type = $type; 
	   $this->_default = $default;
	   $this->_validation = $validation;
	   $this->_possopts = $possopts;
    }
	   
	public function get_name(){
		return $this->_name;
	}

	public function get_type(){
		return $this->_type;
	}
	
	public function get_default(){
		return $this->_default;
	}
	
	public function get_validation(){
		return $this->_validation;
	}
	
	public function get_options(){
		return $this->_possopts;
	}
	

}