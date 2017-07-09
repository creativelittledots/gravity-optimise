<?php
	
	namespace OptimalGravity;
	
	class NotificationFormat {
		
		public static function init() {
			
			return new static();
			
		}
		
		public function __construct() {
			
			add_filter( 'gform_notification', array($this, 'notificationFormat'), 10, 3);
			add_filter( 'gform_pre_send_email', array($this, 'setGformTemplate') );
			
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
        
        public function setGformTemplate( $email ) {
			
			$email['message'] = $this->setToWooTemplate( $email['message'], 'text/html', $email['subject'] );

			return $email;
			
		}
		
		/**
		 * Add the template to the message body.
		 *
		 * Looks for %message% into the template and replaces it with the message.
		 *
		 * @since 0.1
		 * @param string $body The message to templatize
		 * @param string $type The type of template to use. Defaults to 'template', which is HTML.
		 *  Use 'plaintext_template' to use the plain-text template.
		 * @return string $email The email surrounded by template
		 */
		public function setToWooTemplate( $body, $type = 'text/html', $heading = ''  ) {
			
			ob_start();
			
			wc()->mailer();
			
			if( $type === 'text/html'  ) {
				
				do_action( 'woocommerce_email_header', $heading );
				
			}
			
			if( $type === 'text/plain' ) {
				
				$body = strip_tags( $body );

				// Decode body
				$body = wp_specialchars_decode( $body, ENT_QUOTES );
				
			} else if ( $type === 'text/html' ) {
				
				// Clean < and > around text links in WP 3.1
				$body = preg_replace( '#<(https?://[^*]+)>#', '$1', $body )
	
				// Convert line breaks & make links clickable
				$body = make_clickable( $body );
				
			}
			
			echo $body;
			
			if( $type === 'text/html' ) {
				
				do_action( 'woocommerce_email_footer' );
				
			}
			
			$content = ob_get_contents();
			
			ob_end_clean();
			
			if( $type === 'text/html' ) {
				
				$content = apply_filters( 'woocommerce_mail_content', $this->styleInline( $content ) );
				
			}

			return $content;
			
		}
		
		/**
		 * Apply inline styles to dynamic content.
		 *
		 * @param string|null $content
		 * @return string
		 */
		public function styleInline( $content ) {
			// make sure we only inline CSS for html emails
			if ( class_exists( 'DOMDocument' ) ) {
				ob_start();
				wc_get_template( 'emails/email-styles.php' );
				$css = apply_filters( 'woocommerce_email_styles', ob_get_clean() );
				// apply CSS styles inline for picky email clients
				try {
					$emogrifier = new \Emogrifier( $content, $css );
					$content    = $emogrifier->emogrify();
				} catch ( \Exception $e ) {
					$logger = wc_get_logger();
					$logger->add( 'emogrifier', $e->getMessage() );
				}
			}
			return $content;
		}
		
	}
	
	NotificationFormat::init();