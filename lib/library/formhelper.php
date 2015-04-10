<?php
class HTMCustomAreasFormHelper{
    
    private $_optionsPage; 
    private $_selectMessageDefault;
 	private $_textMessageDefault;
	private $_errors; 
	private $_hasError; 
    
    public function __construct($optionsPage = false){ 
        $this->_optionsPage = $optionsPage;
        $this->_selectMessageDefault = "You must select a valid value";
		$this->_textMessageDefault = "You must enter a value";
		$this->_errors = array();
		$this->_hasError = false; 
    }
    
    /*
    * Validate a textbox
    */
    public function validate_textbox($option_name, $input, $default, $rules = array()){       
        
    }
	
	public function validate_text($option_name, $input, $default, $rules = array()){
		//Init the error 
		$this->_errors[$option_name] = '';
		
		//Validate exists
		if(!array_key_exists($option_name, $input)){
			return $default[$option_name];
		}
		
		//Validate required
		if(array_key_exists('required', $rules)){
			if(strlen(trim($input[$option_name])) <= 0){
				 $this->add_error($option_name, $option_name.'_text_error', $this->_textMessageDefault , 'error');
				 return sanitize_text_field($input[$option_name]);
			}
		}
		
		///Validate max chars
		if(array_key_exists('max_chars', $rules)){
			$char_len = $rules['max_chars'];

			if(strlen(trim(strip_tags($input[$option_name])))  > $char_len){
				 $this->add_error($option_name, $option_name.'_text_error', "You must enter less than {$char_len} characters" , 'error');
				 return sanitize_text_field($input[$option_name]);
			}
		}
				
		//Validate is link 
		if(array_key_exists('is_link', $rules)){
			if(strlen($input[$option_name]) > 0){
				if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $input[$option_name])) {
					$this->add_error($option_name, $option_name.'_text_error', "You must enter an absolute url" , 'error');
					return $input[$option_name];
				}
			}
		}
		
		return sanitize_text_field($input[$option_name]);
	}
    
        
    /*
    * Validate a textbox
    */
    public function validate_select($option_name, $input, $default, $option_values){       
      	//Init the error 
		$this->_errors[$option_name] = '';
		
	    if( isset( $input[$option_name] ) && in_array($input[$option_name], $option_values)){
            return $input[$option_name];
        }
        else{
            $this->add_error($option_name, $option_name.'_select_error', $this->_selectMessageDefault, 'error');
            return $default;
        }
    }
    
    
    /*
    * Validate a checkbox
    */
    public function validate_checkbox($option_name, $input, $default, $required = false){
		//Init the error 
		$this->_errors[$option_name] = '';
		       
        if( isset( $input[$option_name] ) && ( 1 == $input[$option_name] ) ){
            return 1;
        }else{
            return $default;
        }
    }
	
	
	/*
    * Validate a checkbox
    */
    public function validate_checkboxarray($option_name, $input, $default, $required = false){
		//Init the error 
		$this->_errors[$option_name] = '';
	
		if(isset($input[$option_name])){
			return $input[$option_name];
		}
		return $default;
    }
	
	/*
	* Get the errors
	*/
	public function get_errors(){
		return $this->_errors;
	}
    
	/*
	* Is there an error ?
	*/  
	public function has_an_error(){
		return $this->_hasError;	
	}
    /*
    * Add form errors 
    * add_error($option_name,$option_name.'_checkerror', 'Please Select an option','error');
    */
    private function add_error($title, $id, $message, $type){
        if($this->_optionsPage){
            add_settings_error(
                $title,                     // Setting title
                $id,            // Error ID
                $message,     // Error message
                $type                      // Type of message
            );
        }
		//Add an error
		$this->_errors[$title] = $message;
		$this->_hasError = true;
    }
	
	
	
	

}