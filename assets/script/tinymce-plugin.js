/*
 * A Tiny MCE Plugin to add a piece of content
 */
(function () {
   //Init form defaults 
   var formDefaults = new Object();
   
   //Load the defaults   
   if(jQuery('#htmcas_title').length > -1){
       formDefaults.title = jQuery('#htmcas_title').val();
       formDefaults.link = jQuery('#htmcas_link').val();
       formDefaults.image = jQuery('#htmcas_featured').val();
       formDefaults.select = jQuery('#htmcas_select').val();
   }
   
    /*
    * Create the plugin
    */
    tinymce.PluginManager.add('HTMCustomAreas_tinymce', function (editor, url) {
        editor.addButton('HTMCustomAreas_tinymce', {
            title: 'My test button',
            icon: 'icon htmlcas-own-icon',
            cmd: 'open_insert'
        });

        editor.addCommand('open_insert', function () {
            	// triggers the thickbox
		var width = jQuery(window).width(), 
                H = jQuery(window).height(), 
                W = ( 720 < width ) ? 720 : width;
                
		W = W - 80;
		H = H - 84;
         
		tb_show( 'HTM Custom Areas', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=htmcas_ajaxsearchouter' );
        });
    });

    jQuery('#htmcas_insert').click(function(){
        insertShortcode();
    });

    function insertShortcode() {
    
        //Get All of the posts to insert 
        var val = jQuery('#htmcas_opts').val();
        val = val.replace(/,+$/, "");
        
            
        if(val == ''){
            alert('You must select at least one post to insert');
            return;
        }

        var showTitle = jQuery('#htmcas_title').val() == 'no' ? ' title="no"' : '';
        var showLink = jQuery('#htmcas_link').val() == 'no' ? ' link="no"' : '';
        var showFeatured = jQuery('#htmcas_featured').val() == 'thumb' ? '' : ' img="' + jQuery('#htmcas_featured').val() + '"';
        
        //Form Value String 
        var string = '[HTMCustomAreas id="' + val + '"' + showTitle + showLink + showFeatured + '/]';

        //Add the content
        tinymce.activeEditor.execCommand('mceInsertContent', false, string);
        
        //Hide the tb
        tb_remove();
        
        //Reset the form 
        resetForm();
    }
    
    /*
    * Reset the insert form
    */
    function resetForm(){
      if(jQuery('#htmcas_title').length > -1){
        jQuery('#htmcas_title').val(formDefaults.title);
        jQuery('#htmcas_link').val( formDefaults.link);
        jQuery('#htmcas_featured').val(formDefaults.image);
        jQuery('#htmcas_select').val(formDefaults.select);
        jQuery('#htmcas_opts').val('');     
        jQuery('#htmcas_selected').empty();
      }
    }
    
})();