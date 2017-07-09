<?php
	
	namespace OptimalGravity;
	
	class FilterMarkup {
		
		var $find = [];
		var $replace = [];
		
		public static function init() {
			
			return new static();
			
		}
		
		public function __construct() {
			
			add_filter( 'gform_get_form_filter', array($this, 'filterMarkup'), 10, 2 );
			
		}
		
		public function filterMarkup( $html ) {
			
        	if( is_admin() ) {
	        	
        		return $html;
        		
    		}
			
			libxml_use_internal_errors(true);
			
			$find = ! empty( $this->settings['find'] ) ? $this->settings['find'] : array();
			
			$replace = ! empty( $this->settings['replace'] ) ? $this->settings['replace'] : array();
			
			return str_replace( $find, $replace, $html );
        	
        }
		
	}
	
	FilterMarkup::init();