
if(typeof jQuery!="undefined"){
	var $ = jQuery;
	jQuery(document).ready(function($) {
		$('body').on('click', '.acf-icon.-minus', function( e ){				
//			return confirm("Delete this Module?");
		});
		$('.acf-flexible-content .layout').addClass('-collapsed');
			var $block_types = [];
			$('.values .layout').each( function(){
				var $block_type = $(this).data('layout');
				if( $('body').hasClass('toplevel_page_global-modules') ){
					var $title = $(this).find("[data-name='" + $block_type + "_name'] input").val();
					alert($title);
				}
				else{
					if( $block_type == 'global_block' ){
						var $title = $(this).find( "select option:selected" ).text();
					}
					else{
						var $title = $(this).find("[data-name='" + $block_type + "_name'] input").val();
					}
				}
				var $handle = $(this).find('.acf-fc-layout-handle');
				if( $title ){
					var $content = $title;	
					$content = "<span class='minor-name'>" +  $handle.html() +" : </span><b>"+$content + "</b>";
					
				}
				else{
					var $content = $handle.html();
				}
				$handle.html( $content );
			});
			window.layout_changed = 0;

			$('#pilot-template-option-title').each(function() {
				$("<input placeholder='Page Title' type='text' />").attr({ id: 'pilot-template-option-title' }).insertBefore(this);
				$('[data-name="add-layout"]').on('click', function(){
					window.layout_changed = 1;
				})
			}).remove();
		$('#new_page').on('click',function(e){
			$(window).unbind();
			e.preventDefault();
			var option_title = $('#pilot-template-option-title').val();
			if( !option_title ){
				alert('Please add a Page Title to continue.')
			}
			else{
				if(!window.layout_changed){
					var page = getParameterByName('page');
					var option_name = $('#option-name').val( );
					var form = document.createElement("form");
		    		var element1 = document.createElement("input"); 
		    		var element2 = document.createElement("input"); 
		    		var element3 = document.createElement("input"); 
				    form.method = "POST";
				    form.action = "/";
				    element1.value=option_name;
				    element1.name="pilot-template-option-name";
				    element2.value=option_title;
				    element2.name="pilot-template-option-title";
				    element3.value=page;
				    element3.name="template-page";
				    form.appendChild(element1);  
				    form.appendChild(element2);  
				    form.appendChild(element3);  
					document.body.appendChild(form);
					form.submit();
		//			window.location = "/?create_page=" + option_name;
				}
				else{
					alert('Blocks have been added to this template. Please click Update to save or Refresh the page.');
				}
			}
		})
	}); 
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

}