<?php
	
	namespace OptimalGravity;
	
	class Nesting {
		
		public static function init() {
			
			return new static();
			
		}
		
		public function __construct() {
			
			add_filter( 'gform_add_field_buttons', array( $this, 'addField' ) );
            add_filter( 'gform_field_type_title' , array( $this, 'setTitle' ), 10, 2 );
            add_action( 'gform_editor_js', array( $this, 'customScripts' ) );
            add_filter( 'gform_field_content', array( $this, 'displayField' ), 10, 5 );
			
		}
		
		/**
		 * Create a new fields group in the Gravity Forms forms editor and add our nesting 'fields' to it.
		 */
		
		public function addField( $field_groups ) {
			
			// add begin nesting button
			
			$nesting_begin_field = array(
				
				'class'		=> 'button',
				'value'		=> __( 'Begin Nesting', 'gravity-forms-integration' ),
				'data-type'	=> 'NestBegin',
				'onclick'	=> 'StartAddField( \'NestBegin\' );'
				
			);
			
			// add end nesting button
			
			$nesting_end_field = array(
				
				'class'		=> 'button',
				'value'		=> __( 'End Nesting', 'gravity-forms-integration' ),
				'data-type'	=> 'NestEnd',
				'onclick'	=> 'StartAddField( \'NestEnd\' );'
				
			);

			foreach ( $field_groups as &$group ) :
				
				$raak_fields_active = false;

				if ( $group["name"] === "nesting" ) :
					
					$raak_fields_active = true;
					
					$group["fields"][] = $nesting_begin_field;
					$group["fields"][] = $nesting_end_field;
					
				endif;

			endforeach;

			if ( !$raak_fields_active ) :
				
				$field_groups[] = array(
					
					'name'		=> 'nesting',
					'label'		=> __( 'Nesting', 'gravity-forms-integration' ),
					'fields'	=> array( $nesting_begin_field, $nesting_end_field )
					
				);
				
			endif;

			return $field_groups;
			
		}
		
		
		/**
		 * Add title to nesting, displayed in Gravity Forms forms editor
		 */
		
		public function setTitle( $title, $field_type ) {
			
			if ( $field_type === "NestBegin" ) :
				
				return __( 'Nest Begin', 'gravity-forms-integration' );
				
			elseif ( $field_type === "NestEnd" ) :
			
				return __( 'Nest End', 'gravity-forms-integration' );
				
			else :
			
				return __( 'Unknown', 'gravity-forms-integration' );
				
			endif;
			
		}
		
		
		/**
		 * JavaSript to add field options to nesting fields in the Gravity forms editor
		 */
		
		public function customScripts() {
			
			wp_register_script( 'nesting', get_stylesheet_directory_uri() . '/js/jquery.gform.nesting.js', array('jquery'), '1.0.0', true );
			
			wp_localize_script( 'nesting', 'rg_delete_field', array(
				'nonce' => wp_create_nonce( 'rg_delete_field' )
			));
			
			wp_enqueue_script( 'nesting' );
			
		}

		public function displayField( $content, $field, $value, $lead_id, $form_id) {
			
			if ( ( ! is_admin() ) && ( $field['type'] == 'NestBegin') ) {
				
				$content = '<ul><li>';

			} elseif ( ( !is_admin() ) && ( $field['type'] == 'NestEnd' ) ) {
				
				$content = '</li></ul>';
				
			}

			return $content;
			
		}
		
	}
	
	Nesting::init();