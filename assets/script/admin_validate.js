/*
* Script to validate the link on the metadata page
*/
jQuery( function ( $ ) {
	
	$('#publish, .save-post-status').click(function(event){		
		var link = $('#htmlcaslink_url');
		if(link.length > 0){
			var val = link.val().trim();
			if(val != ''){
				
				var regex = /\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i;
				
				if(!regex.test(val)){
					link.after('<span class="error">You must enter an absolute url eg. www.test.com</span>');
					event.preventDefault();
					return false;
				}else{
					link.parent().find('.error').each(function(){
						$(this).remove(); 
					});
				}
			}
		}
	});		
}); 
