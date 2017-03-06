jQuery(document).ready(function($){



    /*
    Our color picker
     */

    $(".theme-admin-color-picker").spectrum({
        showInput: true,
        allowEmpty: true,
        showAlpha: true,
        cancelText: 'Close',
        chooseText: 'Pick',
        preferredFormat: "rgb"
    });



    /*
    Theme Options Save Function
     */

        jQuery('#theme-admin-options').on('submit',function () {
            jQuery(".theme-options-loading,.theme-options-overlay").show();
            var b = jQuery(this).serialize();
            jQuery.post('options.php', b).error(
                function () {
                    jQuery("#theme-admin-messages").html('<div class="notice notice-error is-dismissible"><p>Settings not saved, please try again</p></div>');
                    jQuery(".theme-options-loading,.theme-options-overlay").hide();
                    return false;

                }).success(function () {
                jQuery("#theme-admin-messages").html('<div class="notice notice-success is-dismissible"><p>Settings saved!</p></div>');
                jQuery(".theme-options-loading,.theme-options-overlay").hide();
                jQuery('#settings-changed').val(0); //reset settings saved warning init

                return true;


            });
            return false;
        });






    /*
    Populates hidden field with select text value
     */

    jQuery(".admin-options-select").change(function(){

        var selected = jQuery(this).find("option:selected").text();

        jQuery(this).next('.hidden-select').val(selected);




    });


    /*
     Populates hidden field with select text value for gfont
     */

    jQuery(".gfont-select").change(function(){

        var selected = jQuery(this).val();

        var is_web_font = jQuery(this).find(':selected').data('font');

        var selected_id = jQuery(this).attr('id');

        var hidden_select = jQuery(this).next('.hidden-select');

        var hidden_enqueue = jQuery('#hidden-enqueue-' + selected_id);

        if(is_web_font == "web") {

            hidden_enqueue.val(selected);

        }else{

            hidden_enqueue.val('');


        }

        hidden_select.val(selected);



    });

    /*
     Add repeater field
     */


    jQuery('body').on('click', '.add-field-btn', function(){



        field_name = jQuery(this).data('name');
        field_id = jQuery(this).data('id');
        field_class = jQuery(this).data('class');



        field = "<div class='repeater-row'><label><span></span><input type='text' name='"+field_name+"' /> <button type='button' class='btn-submit theme-options-uploader' data-return='"+field_id+"'>Upload</button> <button class='theme-options-repeater-remove' type='button'>Remove</button><div class='img-preview "+field_id+"-preview'></div></label></div>";


        jQuery("#upload-repeater").append(field);






    });


    jQuery('body').on('click', '.theme-options-uploader', function(e) {
        e.preventDefault();
        return_class = jQuery(this).data('return');
        return_field = jQuery(this).prev('input');
        return_preview = jQuery('.'+return_class+'-preview');
        bg_options = jQuery(this).parent().find(".theme-options-bg-layout");


        var image = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open()
            .on('select', function(e){
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                jQuery(return_field).val(image_url);
                jQuery(return_preview).html('<img src="'+image_url+'" />');
                if ( jQuery( bg_options ).length ) {

                    jQuery(bg_options).css('display', 'inline');

                }

            });


    });

    jQuery('body').on('click', '.theme-options-repeater-remove', function(e) {
        e.preventDefault();


        jQuery(this).parent().parent().remove();


    });



    /*
    Our theme restore function
     */
    jQuery('.do-theme-restore').click(function(){

        var import_code = jQuery("#import_settings").val();

        if(import_code != "") {

            var confirm_import = confirm('Are you sure you want to do this? This will override all existing theme settings?');

            if (confirm_import) {

                var data_url = jQuery(this).data('ajax');

                jQuery.post(data_url,
                    {
                        import_settings: import_code
                    },
                    function (response) {
                        if(response == "success"){
                            jQuery("#theme-admin-messages").html('<div class="notice notice-success is-dismissible"><p>Theme restore complete, please refresh this page now.</p></div>');
                        }else{
                            jQuery("#theme-admin-messages").html('<div class="notice notice-error is-dismissible"><p>Theme restore error, to prevent data loss please click \'Save Settings\' now!</p></div>');

                        }
                    });


            }

        }
        else{

            alert("Import settings cannot be empty!");
        }
    });



    /*
    Our Back Up Function
     */


    jQuery('.do-theme-backup').on('click',function(){



        //Dave first to update DB
        var save_first = jQuery('#theme-admin-options' ).submit();


        if(save_first){


            //If saved show download button
            $(this).hide();
            $('.do-theme-backup-action').show();

        }else{

            //Error
            jQuery("#theme-admin-messages").html('<div class="notice notice-error is-dismissible"><p>We were unable to save your settings before generating your back up file. Please click "Save Settings" then refresh this page.</p></div>');


        }








    });


    /*
    Download backup file
     */
    jQuery('.do-theme-backup-action').on('click', function(){

        var data_url = jQuery(this).data('ajax');

        window.location = data_url;

    });



    /*
    If settings have been changed, we reset the import / export form buttons, we also show set a hidden field to ! to alter us that settings are not saved yet
     */

    jQuery("#theme-admin-options").on('change',function(){

        jQuery('.do-theme-backup').show();
        jQuery('.do-theme-backup-action').hide();
        jQuery('#settings-changed').val(1); // settings not saved yet!

    });



    /*
    If settings are not saved lets alert the user
     */
    jQuery(window).bind('beforeunload', function(){
        var unsaved_changes = jQuery("#settings-changed").val();
        if(unsaved_changes == 1) {
            return 'Are you sure you want to leave?';
        }
    });




   /*
   Our main UI nav tabs
    */
    jQuery(function ($) {
        var items = $('#theme-admin-nav-tabs>ul>li').each(function () {
            $(this).click(function () {
                items.removeClass('current');
                $(this).addClass('current');
                $('#theme-admin-nav-tabs>div.tab-content').hide().eq(items.index($(this))).show();



                window.location.hash = $(this).data('tab');



            });
        });

        if (location.hash) {
            showTab(location.hash.replace("#", ""));
        }
        else {
            showTab("overview");
        }

        function showTab(tab) {
            $("li[data-tab*='"+tab+"']").click();
        }

        $(window).hashchange(function () {
            showTab(location.hash.replace("#", ""));
        });

        $(window).hashchange();
    });


    jQuery(function() {

        if(jQuery( "#upload-repeater" ).length > 0) {

            jQuery("#upload-repeater").sortable();

        }

    });







});



