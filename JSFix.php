<?php
	
	namespace OptimalGravity;
	
	class JsFix {
		
		public static function init() {
			
			return new static();
			
		}
		
		public function __construct() {
			
			add_filter( 'gform_cdata_open', array($this, 'openJsWrapper') );
			add_filter( 'gform_cdata_close', array($this, 'closeJsWrapper') );
			
		}
		
		public function openJsWrapper( $content = '' ) {
			
        	$content = 'document.addEventListener( "DOMContentLoaded", function() { ';
        	
        	return $content;
        	
        }
        
        public function closeJsWrapper( $content = '' ) {
	        
        	$content = ' }, false );';
        	
        	return $content;
        	
        }
		
	}
	
	JsFix::init();