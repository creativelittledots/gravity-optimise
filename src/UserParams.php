<?php
	
	namespace OptimalGravity;
	
	class UserParams {
		
		public static function init() {
			
			return new static();
			
		}
		
		public function __construct() {
			
			add_filter( 'gform_field_value_user_firstname', array($this, 'getUserData' ) );
			add_filter( 'gform_field_value_user_lastname', array($this, 'getUserData' ) );
			add_filter( 'gform_field_value_display_name', array($this, 'getUserData' ) );
			add_filter( 'gform_field_value_user_email', array($this, 'getUserData' ) );
			add_filter( 'gform_field_value_user_login', array($this, 'getUserData' ) );
			
		}
		
		public function getUserData() {
			
			return wp_get_current_user()->{ str_replace( 'gform_field_value_', '', current_filter() ) };
        	
        }
		
	}
	
	UserParams::init();