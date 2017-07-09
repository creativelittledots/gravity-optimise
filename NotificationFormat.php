<?php
	
	namespace OptimalGravity;
	
	class NotificationFormat {
		
		public static function init() {
			
			return new static();
			
		}
		
		public function __construct() {
			
			add_filter( 'gform_notification', array($this, 'notificationFormat'), 10, 3);
			
		}
		
		public function notificationFormat( $html ) {
			
        	// is_plugin_active is not availble on front end
			if( !is_admin() )
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				
			// does WP Better Emails exists and activated ?
			if( !is_plugin_active('wp-better-emails/wpbe.php') )
				return $notification;
				
			// some other checks here
		
			// change notification format to text from the default html
		    $notification['message_format'] = "text";
			// disable auto formatting so you don't get double line breaks
			$notification['disableAutoformat'] = true;
		
		    return $notification;
        	
        }
		
	}
	
	NotificationFormat::init();