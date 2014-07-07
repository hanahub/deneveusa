jQuery(document).ready(function(){

/*RESPONSIVE NAVIGATION, COMBINES MENUS EXCEPT FOR FOOTER MENU*/

	jQuery('#nav').not('#footer .menu, #footer-widgets .menu').wrap('<div id="nav-responsive" class="nav-responsive">');
	jQuery('#wrap').prepend('<div id="mobile_menu_area"><div id="pull" class="closed"><span>Navigation</span></div></div>');	
	
	sf_duplicate_menu( jQuery('.nav-responsive ul'), jQuery('#pull'), 'mobile_menu', 'mobile_menu' );
	
			
			function sf_duplicate_menu( menu, append_to, menu_id, menu_class ){
				var jQuerycloned_nav;
				
				menu.clone().attr('id',menu_id).removeClass().attr('class',menu_class).appendTo( append_to );
				jQuerycloned_nav = append_to.find('> ul');
				jQuerycloned_nav.find('.menu_slide').remove();
				jQuerycloned_nav.find('li:first').addClass('first-item');
				
				append_to.click( function(){
					if ( jQuery(this).hasClass('closed') ){
						jQuery(this).removeClass( 'closed' ).addClass( 'opened' );
						jQuerycloned_nav.slideDown( 300 );
					} else {
						jQuery(this).removeClass( 'opened' ).addClass( 'closed' );
						jQuerycloned_nav.slideUp( 300 );
					}
					return false;
				} );
				
				append_to.find('a').click( function(event){
					event.stopPropagation();
				} );
			}


});