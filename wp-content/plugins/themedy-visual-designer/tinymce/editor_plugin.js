function themedy_js_querystring(ji) {

	hu = window.location.search.substring(1);
	gy = hu.split( "&" );
	for (i=0;i<gy.length;i++) {
	
		ft = gy[i].split( "=" );
		if (ft[0] == ji) {
		
			return ft[1];
		
		} // End IF Statement
		
	} // End FOR Loop
	
} // End themedy_js_querystring()
	
(
	
	function(){
	
		// Get the URL to this script file (as JavaScript is loaded in order)
		// (http://stackoverflow.com/questions/2255689/how-to-get-the-file-path-of-the-currenctly-executing-javascript-code)
		
		var scripts = document.getElementsByTagName( "script"),
		src = scripts[scripts.length-1].src;
		
		if ( scripts.length ) {
		
			for ( i in scripts ) {

				var scriptSrc = '';
				
				if ( typeof scripts[i].src != 'undefined' ) { scriptSrc = scripts[i].src; } // End IF Statement
	
				var txt = scriptSrc.search( 'shortcode-generator' );
				
				if ( txt != -1 ) {
				
					src = scripts[i].src;
				
				} // End IF Statement
			
			} // End FOR Loop
		
		} // End IF Statement
		
		var icon_url = '../wp-content/plugins/themedy-visual-designer/images/shortcode-icon.png';
	
		tinymce.create(
			"tinymce.plugins.ThemedyShortcodes",
			{
				init: function(d,e) {
						d.addCommand( "themedyVisitThemedy", function(){ window.open( "http://themedy.com/" ) } );
						
						d.addCommand( "themedyOpenDialog",function(a,c){
							
							// Grab the selected text from the content editor.
							selectedText = '';
						
							if ( d.selection.getContent().length > 0 ) {
						
								selectedText = d.selection.getContent();
								
							} // End IF Statement
							
							themedySelectedShortcodeType = c.identifier;
							themedySelectedShortcodeTitle = c.title;
							
							
							jQuery.get(e+"/dialog.php",function(b){
								
								jQuery( '#themedy-options').addClass( 'shortcode-' + themedySelectedShortcodeType );
								jQuery( '#themedy-preview').addClass( 'shortcode-' + themedySelectedShortcodeType );
								
								// Skip the popup on certain shortcodes.
								
								switch ( themedySelectedShortcodeType ) {
							
									// Highlight
									
									case 'highlight':
								
									var a = '[highlight]'+selectedText+'[/highlight]';
									
									tinyMCE.activeEditor.execCommand( "mceInsertContent", false, a);
								
									break;
									
									// Dropcap
									
									case 'dropcap':
								
									var a = '[dropcap]'+selectedText+'[/dropcap]';
									
									tinyMCE.activeEditor.execCommand( "mceInsertContent", false, a);
								
									break;
							
									default:
									
									jQuery( "#themedy-dialog").remove();
									jQuery( "body").append(b);
									jQuery( "#themedy-dialog").hide();
									var f=jQuery(window).width();
									b=jQuery(window).height();
									f=720<f?720:f;
									f-=80;
									b-=84;
								
								tb_show( "Insert Themedy "+ themedySelectedShortcodeTitle +" Shortcode", "#TB_inline?width="+f+"&height="+b+"&inlineId=themedy-dialog" );jQuery( "#themedy-options h3:first").text( "Customize the "+c.title+" Shortcode" );
								
									break;
								
								} // End SWITCH Statement
							
							}
													 
						)
						 
						} 
					);
						
						// d.onNodeChange.add(function(a,c){ c.setDisabled( "themedythemes_shortcodes_button",a.selection.getContent().length>0 ) } ) // Disables the button if text is highlighted in the editor.
					},
					
				createControl:function(d,e){
				
						if(d=="themedy_shortcodes_button"){
						
							d=e.createMenuButton( "themedy_shortcodes_button",{
								title:"Insert Themedy Shortcode",
								image:icon_url,
								icons:false
								});
								
								var a=this;d.onRenderMenu.add(function(c,b){
								
									a.addWithDialog(b,"Button","button" );
								b.addSeparator();
									a.addWithDialog(b,"Info Box","box" );
									c=b.addMenu({title:"Typography"});
										a.addWithDialog(c,"Dropcap","dropcap" );
										a.addWithDialog(c,"Quote","quote" );
										a.addWithDialog(c,"Highlight","highlight" );
										a.addWithDialog(c,"Abbreviation","abbr" );
									a.addWithDialog(b,"Related Posts","related" );
									a.addWithDialog(b,"Styled List","list" );
								b.addSeparator();
									a.addWithDialog(b,"Column Layout","column" );
								b.addSeparator();
									c=b.addMenu({title:"Dividers"});
										a.addImmediate(c,"Horizontal Rule","[hr] " );
										a.addImmediate(c,"Divider","[divider] " );
										a.addImmediate(c,"Flat Divider","[divider_flat] " );
									c=b.addMenu({title:"Social Media Share"});
										a.addWithDialog(c,"Twitter","twitter" );
										a.addWithDialog(c,"Reddit","reddit" );
										a.addWithDialog(c,"StumbleUpon","stumble" );
										a.addWithDialog(c,"Digg","digg" );
										a.addWithDialog(c,"Facebook","facebook" );
										a.addWithDialog(c,"Google +1 Button","google_plusone" );
								 });
							return d
						
						} // End IF Statement
						
						return null
					},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})},
				
				addWithDialog:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "themedyOpenDialog",false,{title:e,identifier:a})}})},
		
				getInfo:function(){ return{longname:"Themedy Shortcode Generator",author:"VisualShortcodes.com",authorurl:"http://visualshortcodes.com",infourl:"http://visualshortcodes.com/shortcode-ninja",version:"1.0"} }
			}
		);
		
		tinymce.PluginManager.add( "ThemedyShortcodes",tinymce.plugins.ThemedyShortcodes)
	}
)();
