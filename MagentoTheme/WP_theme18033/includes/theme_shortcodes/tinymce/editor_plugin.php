<?php 
header("Content-Type:text/javascript");

//Setup URL to WordPress
$absolute_path = __FILE__;
$path_to_wp = explode( 'wp-content', $absolute_path );
$wp_url = $path_to_wp[0];

//Access WordPress
require_once( $wp_url.'/wp-load.php' );

//URL to TinyMCE plugin folder
$plugin_url = get_template_directory_uri().'/includes/theme_shortcodes/tinymce/';
?>
(function(){
	
	var icon_url = '<?php echo $plugin_url; ?>' + 'images/icon_shortcodes.png';

	tinymce.create(
		"tinymce.plugins.MyThemeShortcodes",
		{
			init: function(d,e) {
					
					d.addCommand( "myThemeOpenDialog",function(a,c){
						
						// Grab the selected text from the content editor.
						selectedText = '';
					
						if ( d.selection.getContent().length > 0 ) {
					
							selectedText = d.selection.getContent();
							
						} // End IF Statement
						
						myThemeSelectedShortcodeType = c.identifier;
						myThemeSelectedShortcodeTitle = c.title;
						
						jQuery.get(e+"/dialog.php",function(b){
							
							jQuery('#shortcode-options').addClass( 'shortcode-' + myThemeSelectedShortcodeType );
							
							// Skip the popup on certain shortcodes.
							
							switch ( myThemeSelectedShortcodeType ) {
								
								// alert
								
								case 'alert':
								
								var a = '[alert]'+selectedText+'[/alert]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// approved
								
								case 'approved':
								
								var a = '[approved]'+selectedText+'[/approved]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// attention
								
								case 'attention':
								
								var a = '[attention]'+selectedText+'[/attention]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// notice
								
								case 'notice':
								
								var a = '[notice]'+selectedText+'[/notice]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// tags
								
								case 'tags':
								
								var a = '[tags]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// dropcap
								
								case 'dropcap':
								
								var a = '[dropcap]'+selectedText+'[/dropcap]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// frame
								
								case 'frame':
								
								var a = '[frame]'+selectedText+'[/frame]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// frame_left
								
								case 'frameleft':
								
								var a = '[frame_left]'+selectedText+'[/frame_left]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// frame_right
								
								case 'frameright':
								
								var a = '[frame_right]'+selectedText+'[/frame_right]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// Horizontal Ruel
								
								case 'hr':
								
								var a = '[hr]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// Small Horizontal Rule
								
								case 'sm_hr':
								
								var a = '[sm_hr]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // spacer
								
								case 'spacer':
								
								var a = '[spacer]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// grid_1
								
								case 'grid_1':
								
								var a = '[grid_1]'+selectedText+'[/grid_1]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
								// grid_2
								
								case 'grid_2':
								
								var a = '[grid_2]'+selectedText+'[/grid_2]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // grid_3
								
								case 'grid_3':
								
								var a = '[grid_3]'+selectedText+'[/grid_3]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // grid_4
								
								case 'grid_4':
								
								var a = '[grid_4]'+selectedText+'[/grid_4]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // grid_5
								
								case 'grid_5':
								
								var a = '[grid_5]'+selectedText+'[/grid_5]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // grid_6
								
								case 'grid_6':
								
								var a = '[grid_6]'+selectedText+'[/grid_6]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // grid_7
								
								case 'grid_7':
								
								var a = '[grid_7]'+selectedText+'[/grid_7]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // grid_8
								
								case 'grid_8':
								
								var a = '[grid_8]'+selectedText+'[/grid_8]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // grid_9
								
								case 'grid_9':
								
								var a = '[grid_9]'+selectedText+'[/grid_9]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // grid_10
								
								case 'grid_10':
								
								var a = '[grid_10]'+selectedText+'[/grid_10]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // grid_11
								
								case 'grid_11':
								
								var a = '[grid_11]'+selectedText+'[/grid_11]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // grid_12
								
								case 'grid_12':
								
								var a = '[grid_12]'+selectedText+'[/grid_12]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                
                
                // one_half (1/2)
								
								case 'one_half':
								
								var a = '[one_half]'+selectedText+'[/one_half]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // one_third (1/3)
								
								case 'one_third':
								
								var a = '[one_third]'+selectedText+'[/one_third]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // two_third (2/3)
								
								case 'two_third':
								
								var a = '[two_third]'+selectedText+'[/two_third]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // one_fourth (1/4)
								
								case 'one_fourth':
								
								var a = '[one_fourth]'+selectedText+'[/one_fourth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // three_fourth (3/4)
								
								case 'three_fourth':
								
								var a = '[three_fourth]'+selectedText+'[/three_fourth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // one_fifth (1/5)
								
								case 'one_fifth':
								
								var a = '[one_fifth]'+selectedText+'[/one_fifth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // two_fifth (2/5)
								
								case 'two_fifth':
								
								var a = '[two_fifth]'+selectedText+'[/two_fifth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // three_fifth (3/5)
								
								case 'three_fifth':
								
								var a = '[three_fifth]'+selectedText+'[/three_fifth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // four_fifth (4/5)
								
								case 'four_fifth':
								
								var a = '[four_fifth]'+selectedText+'[/four_fifth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // one_sixth (1/6)
								
								case 'one_sixth':
								
								var a = '[one_sixth]'+selectedText+'[/one_sixth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // five_sixth (5/6)
								
								case 'five_sixth':
								
								var a = '[five_sixth]'+selectedText+'[/five_sixth]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                 // blockquote
								
								case 'blockquote':
								
								var a = '[blockquote]'+selectedText+'[/blockquote]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
                
                // clear
								
								case 'clear':
								
								var a = '[clear]';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
								
                
                // toggle
								
								case 'toggle':
								
								var a = '[toggle title="Your title goes here"]Your content goes here.[/toggle] ';
								
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								
								break;
															
								default:
								
								jQuery("#dialog").remove();
								jQuery("body").append(b);
								jQuery("#dialog").hide();
								var f=jQuery(window).width();
								b=jQuery(window).height();
								f=720<f?720:f;
								f-=80;
								b-=84;
							
							tb_show("Insert "+ myThemeSelectedShortcodeTitle +" Shortcode", "#TB_inline?width="+f+"&height="+b+"&inlineId=dialog");jQuery("#shortcode-options h3:first").text(""+c.title+" Shortcode Settings");
							
								break;
							
							} // End SWITCH Statement
						
						}
												 
					)
					 
					} 
				);

				},
					
				createControl:function(d,e){
				
						if(d=="shortcodes_button"){
						
							d=e.createMenuButton("shortcodes_button",{
								title:"Insert Shortcode",
								image:icon_url,
								icons:false
								});
								
								var a=this;d.onRenderMenu.add(function(c,b){
                	c=b.addMenu({title:"Basic"});
                  	a.addWithDialog(c,"Recent Posts","recentposts");
                    a.addWithDialog(c,"Recent Testimonials","recenttesti");
                    a.addWithDialog(c,"Popular Posts","popularposts");
										a.addWithDialog(c,"Recent Comments","recentcomments");
										a.addWithDialog(c,"Tags","tags");
                  c=b.addMenu({title:"Grid Columns"});
										a.addWithDialog(c,"grid_1","grid_1");
										a.addWithDialog(c,"grid_2","grid_2");
                    a.addWithDialog(c,"grid_3","grid_3");
                    a.addWithDialog(c,"grid_4","grid_4");
                    a.addWithDialog(c,"grid_5","grid_5");
                    a.addWithDialog(c,"grid_6","grid_6");
                    a.addWithDialog(c,"grid_7","grid_7");
                    a.addWithDialog(c,"grid_8","grid_8");
                    a.addWithDialog(c,"grid_9","grid_9");
                    a.addWithDialog(c,"grid_10","grid_10");
                    a.addWithDialog(c,"grid_11","grid_11");
                    a.addWithDialog(c,"grid_12","grid_12");
										a.addWithDialog(c,"Clear Row","clear");
                  c=b.addMenu({title:"Fluid Columns"});
										a.addWithDialog(c,"1/2","one_half");
										a.addWithDialog(c,"1/3","one_third");
                    a.addWithDialog(c,"2/3","two_third");
                    a.addWithDialog(c,"1/4","one_fourth");
                    a.addWithDialog(c,"3/4","three_fourth");
										a.addWithDialog(c,"1/5","one_fifth");
                    a.addWithDialog(c,"2/5","two_fifth");
                    a.addWithDialog(c,"3/5","three_fifth");
                    a.addWithDialog(c,"4/5","four_fifth");
                    a.addWithDialog(c,"1/6","one_sixth");
                    a.addWithDialog(c,"5/6","five_sixth");
                  c=b.addMenu({title:"HTML"});
										a.addWithDialog(c,"Button","button");
										a.addWithDialog(c,"Drop Cap","dropcap");
                    a.addWithDialog(c,"Blockquote","blockquote");
										a.addWithDialog(c,"Frame","frame");
										a.addWithDialog(c,"Frame Left","frameleft");
										a.addWithDialog(c,"Frame Right","frameright");
										a.addWithDialog(c,"Horizontal Rule","hr");
										a.addWithDialog(c,"Small Horizontal Rule","sm_hr");
                    a.addWithDialog(c,"Spacer","spacer");
									c=b.addMenu({title:"Alert Boxes"});
										a.addWithDialog(c,"Alert","alert");
										a.addWithDialog(c,"Approved","approved");
										a.addWithDialog(c,"Attention","attention");
										a.addWithDialog(c,"Notice","notice");
									c=b.addMenu({title:"Audio & Video"});b.addSeparator();
										a.addWithDialog(c,"Audio","audio");
										a.addWithDialog(c,"Video","video");
									a.addWithDialog(b,"Toggle","toggle");
									a.addWithDialog(b,"Google Map","map");

							});
							
							return d
						
						} // End IF Statement
						
						return null
					},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand("mceInsertContent",false,a)}})},
				
				addWithDialog:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand("myThemeOpenDialog",false,{title:e,identifier:a})}})},
		
				getInfo:function(){ return{longname:"Shortcode Generator",author:"VisualShortcodes.com",authorurl:"http://visualshortcodes.com",infourl:"http://visualshortcodes.com/shortcode-ninja",version:"1.0"} }
			}
		);
		
		tinymce.PluginManager.add("MyThemeShortcodes",tinymce.plugins.MyThemeShortcodes)
	}
)();
