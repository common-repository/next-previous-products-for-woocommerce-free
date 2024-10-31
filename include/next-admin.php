<?PHP
//version: 1.1
//Text Domain: next-previous-product-woocommerce-free

//Admin menu hooks
  add_action( 'admin_menu', 'nssw_star_free_create_menu' );
  add_action( 'admin_init', 'nssw_star_free_settings' );

//function for creating admin menu
function nssw_star_free_create_menu() {
	//add_menu_page('Next / Previous Product for Woocommerce', 'Next/Previous Product', 'administrator', __FILE__, 'nssw_star_free_admin_menu' , plugins_url('/img/nssw-icon.png', __FILE__) );
	add_submenu_page( 'woocommerce', esc_html__('Next / Previous Product Free','next-previous-product-woocommerce-free'), esc_html__('Next / Previous Product Free','next-previous-product-woocommerce-free'), 'manage_options', 'next-previous-product-woocommerce-free', 'nssw_star_free_admin_menu' ); 
}


//Register settings that will be used 
function nssw_star_free_settings() { 

  register_setting( 'nssw-option-group', 'nssw-enabled' );
  register_setting( 'nssw-option-group', 'nssw-location' );
  register_setting( 'nssw-option-group', 'nssw-applies' );
  register_setting( 'nssw-option-group', 'nssw-useimage' );
  register_setting( 'nssw-option-group', 'nssw-arrow' );
  register_setting( 'nssw-option-group', 'nssw-arrow-left' );
  register_setting( 'nssw-option-group', 'nssw-arrow-right' );
  register_setting( 'nssw-option-group', 'nssw-arrow-width' );
  register_setting( 'nssw-option-group', 'nssw-arrow-height' );
  register_setting( 'nssw-option-group', 'nssw-text-size' );
  register_setting( 'nssw-option-group', 'nssw-text-color' );
  register_setting( 'nssw-option-group', 'nssw-custom-css' );
  register_setting( 'nssw-option-group', 'nssw-usetitle' );
  register_setting( 'nssw-option-group', 'nssw-float-background' );
  register_setting( 'nssw-option-group', 'nssw-float-border' );

}


//Main function - admin menu
function nssw_star_free_admin_menu() {
	
	//control access permissions
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	//main form
	echo '<div class="wrap"><form method="post" action="options.php">'; 
	settings_fields( 'nssw-option-group' );

        //get arrow images?
        $img_left=wp_get_attachment_image_src(get_option('nssw-arrow-left'));
        $img_right=wp_get_attachment_image_src(get_option('nssw-arrow-right'));

	wp_enqueue_media();

?>
<!--TITLE-->
<h1><?php echo esc_html__( 'NEXT / PREVIOUS PRODUCT FOR WOOCOMMERCE - FREE','next-previous-product-woocommerce-free');?></h1>
<hr>
<table class="form-table">

<!--Enabled/Disabled-->
<tr valign="top">
<th colspan="2">
<input type='checkbox' name='nssw-enabled' value='true' <?php if (get_option('nssw-enabled',true)) echo 'checked';?> /><?php echo esc_html__( 'Enabled','next-previous-product-woocommerce-free');?></th>
</tr>

<!--Text color-->
<tr id="imageoption2" valign="top">
<th scope="row"><?php echo esc_html__( 'Text color','next-previous-product-woocommerce-free');?></th>
<td><input class="colorpicker" type="text" name="nssw-text-color" value="<?php echo esc_attr( get_option('nssw-text-color','#000000') ); ?>" /></td>
</tr>

<!--Use product title?-->
<tr valign="top">
<th colspan="2">
<input type='checkbox' name='nssw-usetitle' value='true' <?php if (get_option('nssw-usetitle',true)) echo 'checked';?> /><?php echo esc_html__( 'Use product title','next-previous-product-woocommerce-free');?>
</th>
</tr>


<!--Free version notice-->
<tr valign="top">
<th colspan="2">
<?php echo  __( 'Note: Advanced customization options are not available in free version. <a style="color:red;" href="http://starblank.com/en/next-previous-products-for-woocommerce-premium/" target="_BLANK">-You can get the premium version here-</a>','next-previous-product-woocommerce-free'); ?>
</tr>

<!--Location-->
<?php $location=get_option('nssw-location',0); ?>
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Location','next-previous-product-woocommerce-free');?></th>
<td><select disabled onchange="nssw_star_free_position_options(this)" name="nssw-location" selected="<?php echo esc_attr( $location ); ?>">
	<option value="0" <?php if ($location=='0') echo 'selected="selected"'; ?>><?php echo esc_html__( 'Over the product','next-previous-product-woocommerce-free');?></option>
</select></td>
</tr>	

<!--Applies to-->
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Applies to','next-previous-product-woocommerce-free');?></th>
<td><select disabled name="nssw-applies" selected="<?php echo esc_attr( get_option('nssw-applies') ); ?>">
        <option value="1" <?php if (get_option('nssw-applies')=='1') echo 'selected="selected"'; ?>><?php echo esc_html__( 'Same category','next-previous-product-woocommerce-free');?></option>
</select></td>
</tr>

<!--Arrow options-->
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Arrow options','next-previous-product-woocommerce-free');?></th>
<td><select disabled onchange="nssw_star_free_arrow_options(this)" name="nssw-arrow" selected="<?php echo esc_attr( get_option('nssw-arrow') ); ?>">
        <option value="0" <?php if (get_option('nssw-arrow')=='0') echo 'selected="selected"'; ?>><?php echo esc_html__( 'Show default arrows','next-previous-product-woocommerce-free');?></option>
</select></td>
</tr>


<!--Use Image?-->
<tr valign="top">
<th colspan="2">
<input disabled type='checkbox' name='nssw-useimage' value='true' <?php if (get_option('nssw-useimage',false)) echo 'checked';?> /><?php echo esc_html__( 'Use product image','next-previous-product-woocommerce-free');?>
</th>
</tr>

<!--Text size-->
<tr id="imageoption1" valign="top">
<th scope="row"><?php echo esc_html__( 'Text size (in px)','next-previous-product-woocommerce-free');?></th>
<td><input disabled style="width:50px;" type="text" name="nssw-text-size" value="<?php echo esc_attr( get_option('nssw-text-size','16') ); ?>" />px</td>
</tr>


<!--Custom css-->
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Custom CSS','next-previous-product-woocommerce-free');?></th>
<td><textarea disabled style="width:50%; height:100px;" name="nssw-custom-css"><?php echo esc_attr( get_option('nssw-custom-css') ); ?></textarea></td>
</tr>


<?php
        echo '</table>';
	do_settings_sections( 'nssw-option-group');
        submit_button();
	echo '</form>';
	echo '</div>';

}


//Add javascript
add_action( 'admin_enqueue_scripts', 'nssw_star_free_javascript' );
function nssw_star_free_javascript($hook) {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker');
	wp_enqueue_script( 'nssw_javascript', plugins_url('functions.js',__FILE__) );
}



//Image load control function
add_action( 'admin_footer', 'nssw_star_free_media_selector_print_scripts' );
function nssw_star_free_media_selector_print_scripts() {
        
        $my_saved_attachment_post_id = get_option( 'image_attachment_id', 0 );
        if (!$my_saved_attachment_post_id) $my_saved_attachment_post_id=0;
        ?><script type='text/javascript'>
                jQuery( document ).ready( function( $ ) {
			
                        // Uploading files
                        var file_frame;
			var position;
                        if (typeof wp.media !== 'undefined') wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                        var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
                        jQuery('#upload_image_button_left, #upload_image_button_right').on('click', function( event ){
				position=$(this).attr("arrow-name");
                                event.preventDefault();
                                // If the media frame already exists, reopen it.
                                if ( file_frame ) {
                                        // Set the post ID to what we want
                                        file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                                        // Open frame
                                        file_frame.open();
                                        return;
                                } else {
                                        // Set the wp.media post id so the uploader grabs the ID we want when initialised
                                        if (typeof wp.media !== 'undefined') wp.media.model.settings.post.id = set_to_post_id;
                                }
                                // Create the media frame.
                                 if (typeof wp.media !== 'undefined') file_frame = wp.media.frames.file_frame = wp.media({
                                        title: 'Select a image to upload',
                                        button: {
                                                text: 'Use this image',
                                        },
                                        multiple: false // Set to true to allow multiple files to be selected
                                });
                                // When an image is selected, run a callback.
				file_frame.on( 'select', function() {
                                        // We set multiple to false so only get one image from the uploader
                                        attachment = file_frame.state().get('selection').first().toJSON();
                                        // Do something with attachment.id and/or attachment.url here
                                        $( '#image_preview_' + position ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                                        $( '#image_attachment_' + position ).val( attachment.id );
                                        // Restore the main post ID
                                        wp.media.model.settings.post.id = wp_media_post_id;
                                });
                                        // Finally, open the modal
                                        file_frame.open();
                        });
                        // Restore the main ID when the add media button is pressed
                        jQuery( 'a.add_media' ).on( 'click', function() {
                                wp.media.model.settings.post.id = wp_media_post_id;
                        });
                });
        </script><?php
}


