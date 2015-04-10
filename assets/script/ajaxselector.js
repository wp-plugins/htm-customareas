/*
 * Selector Ajax Lookup
 */
jQuery(function (jQuery) {

    jQuery(document).ready(function () {

        jQuery('#htmcas_search').keyup(function () {
            //
            var nonce = jQuery('#HTMCustomAreas_ajax');
            
            

            //Just validate
            if (nonce.length == 0 || jQuery(this).length == 0) {
                return;
            }

            var input = jQuery(this).val();


            var thedata = {
                action: HTMCustomAreas_params.action,
                HTMCustomAreas_ajax: nonce.val(),
                input: input
            };

            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: HTMCustomAreas_params.ajaxurl,
                data: thedata,
                success: function (response) {

                    if (response.data == "success") {

                        //Get the messge
                        var contents = response.supplemental.message;

                        //Empty options
                        jQuery('#htmcas_select').empty();
                        var theval = jQuery('#htmcas_opts').val();
                       
                        if(theval.length > -1){
                            for (var i = 0; i < contents.length; i++) {
                                var display = theval.indexOf(contents[i].ID + ",") > -1 ? 'style="display:none;"' : '';
                                jQuery('#htmcas_select').append('<div ' + display + '><span>' + contents[i].post_title + '</span><input type="button" class="htmcas_post" name="htmcas_post" id="htmcas_post[]" value="+" data-value="' + contents[i].ID + '"/></div>');
                            }
                        }
                    }
                }
            });

        });

        //Respond to mouseclick
        jQuery('body').on('click', '.htmcas_post', function (event) {
            event.preventDefault();

            //Gett attributes
            var attr = jQuery(this).attr('data-value');
            var span = jQuery(this).parent().find('span').text();

            //Add to hidden input
            jQuery('#htmcas_opts').val(jQuery('#htmcas_opts').val() + attr + ",");

            //Add to the second holder
            jQuery('#htmcas_selected').append('<div><span>' + span + '</span><input type="button" class="htmcas_post_rem" name="htmcas_post_rem" id="htmcas_post_rem" value="-" data-value="' + attr + '"/></div>');

            //Hide it
            jQuery(this).parent().hide();

        });

        jQuery('body').on('click', '.htmcas_post_rem', function (event) {
            event.preventDefault();

            //Gett attributes
            var attr = jQuery(this).attr('data-value');
            var span = jQuery(this).parent().find('span').text();

            var newstr = jQuery('#htmcas_opts').val().replace(attr + ',', '');

            jQuery('#htmcas_opts').val(newstr);

            jQuery('#htmcas_select').find("[data-value='" + attr + "']").parent().show();

            jQuery(this).parent().remove();
        });
    });
});