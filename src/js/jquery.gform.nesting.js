jQuery(document).ready(function($) {
	
	fieldSettings["NestBegin"] = ".label_setting, .css_class_setting, .conditional_logic_field_setting";
	fieldSettings["NestEnd"] = "";
	
	function nestingExist() {
		
		var nestingsCount = jQuery( '#gform_fields .gform_nesting' ).length;
		
		return nestingsCount;
		
	}
	
	jQuery(document).bind('gform_field_deleted', function( event, form, field ){
	
		var nestingClosed = true;
		
		jQuery.each( form.fields, function( index, value ) {
			
			if ( typeof value.type != "undefined" ) {
				
				if ( value.type == 'NestBegin' ) {
					
					nestingClosed = false;
					
				} else if ( value.type == 'NestEnd' ) {
					
					console.log( value );
					
					if ( nestingClosed ) {
						
						deleteFieldset( value.id );
						
						return;
						
					}
					
					nestingClosed = true;
					
				}
				
			}
			
		});
	
	});
	
	jQuery(document).bind( 'gform_field_added', function( event, form, field ) {
		
		if ( field['type'] == 'NestBegin' || field['type'] == 'NestEnd' ) {
			
			var nestingClosed = true;
			var index = 1;
			
			jQuery.each( form.fields, function( index, value ) {
				
				if ( typeof value.type != "undefined" ) {
					
					if ( value.type == 'NestBegin' ) {
						
						if ( nestingClosed ) {
							
							nestingClosed = false;
							
						} else {
							
							StartAddField( 'NestEnd', index );
							
							nestingClosed = true;
							
							return;
							
						}
						
					} else if ( value.type == 'NestEnd' ) {
						
						if ( nestingClosed ) {
						
							StartAddField( 'NestBegin', index );
							
							return;
							
						} else {
							
							nestingClosed = true;
							
						}
						
					}
					
				}
				
				index++;
				
			});
			
			if ( !nestingClosed ) {
				
				StartAddField( 'NestEnd' );
				
			}
			
		}
	
	} );
	
	function deleteFieldset( fieldId ) {
	
		jQuery('#gform_fields li#field_' + fieldId).addClass('gform_pending_delete');
		
		var mysack = new sack(ajaxurl);
		
		mysack.execute = 1;
		mysack.method = 'POST';
		
		mysack.setVar("action", "rg_delete_field");
		mysack.setVar("rg_delete_field", rg_delete_field.nonce);
		mysack.setVar("form_id", form.id);
		mysack.setVar("field_id", fieldId);
		
		mysack.onError = function () {
			alert('Ajax error while deleting field.');
		};
		
		mysack.runAJAX();
		
		return true;
	
	}
	
});

