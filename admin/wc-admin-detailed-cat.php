<?php	

	/**
	 * Add form to admin panel category
	 */
	function add_category_detailed_field () {
	global $woocommerce;
		?>
		<div class="form-field">
			<label><?php _e('Detailed Category', 'woocommerce'); ?></label>
			<input type="checkbox" id="product_cat_detailed_id" name="product_cat_detailed_id" style="width:1em;" value="<?php echo $is_detailed_category; ?>" />
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			// on first execution hide detailed form
			jQuery('#description_cat_image_form').hide();
			
			// show / hide image for description
			jQuery('#product_cat_detailed_id').change(function($){
				var isChecked = ( jQuery('#product_cat_detailed_id').attr('checked') == 'checked' ); 
				jQuery('#product_cat_detailed_id').val(isChecked)
				if (isChecked ) {
					jQuery('#description_cat_image_form').show('slow');			
				} else {
					jQuery('#description_cat_image_form').hide('slow');
				}
			});
		});
		</script>
		<?php
	}
	add_action( 'product_cat_add_form_fields', 'add_category_detailed_field',178 );
	
	/**
	 * Edit form to admin panel category
	 */
	function edit_category_detailed_field( $term, $taxonomy ) {
	global $woocommerce;
		$image 			= '';
		$is_detailed_category 	= get_woocommerce_term_meta( $term->term_id, '_woocommerce_detailed_category', true );
		($is_detailed_category)?$checked="checked":$checked="";
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e('Detailed Category', 'woocommerce'); ?></label></th>
			<td><input type="checkbox" id="product_cat_detailed_id" name="product_cat_detailed_id" style="width:1em;" value="<?php echo $is_detailed_category; ?>" <?php  echo $checked; ?>/></td>
		</tr>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			// on first execution hide detailed form if needed
			isChecked = jQuery('#product_cat_detailed_id').val(); 
			if (isChecked) {
				jQuery('#description_cat_image_form').show();
			} else {
				jQuery('#description_cat_image_form').hide();
			}
			
			// show / hide image for description
			jQuery('#product_cat_detailed_id').change(function($){
				var isChecked = ( jQuery('#product_cat_detailed_id').attr('checked') == 'checked' ); 
				jQuery('#product_cat_detailed_id').val(isChecked)
				if (isChecked ) {
					jQuery('#description_cat_image_form').show('slow');			
				} else {
					jQuery('#description_cat_image_form').hide('slow');
				}
			});
		});
		</script>
		<?php
		
	}
	add_action( 'product_cat_edit_form_fields','edit_category_detailed_field', 178,2 );
	
	/**
	 * save meta data
	 */
	function category_detailed_field_save( $term_id, $tt_id, $taxonomy ) {
		//if ( !isset( $_POST['description_cat_image_id'] ) ) return;
		
		if($_POST['product_cat_detailed_id']) {
			update_woocommerce_term_meta( $term_id, '_woocommerce_detailed_category', 1 );
		}
		else {
			delete_woocommerce_term_meta( $term_id, '_woocommerce_detailed_category');
			//update_woocommerce_term_meta( $term_id, '_woocommerce_detailed_category',0);
		}
	}
	add_action( 'created_term', 'category_detailed_field_save', 178,4 );
	add_action( 'edit_term','category_detailed_field_save', 178,4 );
		
	/**
	 * Add the meta for description image for category.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @return void
	 *
	 * based on woocommerce-admin-taxonomies.php
	 */
	function add_category_detailed_description_image () {
	global $woocommerce;
	
	?>
	<div id="description_cat_image_form" class="form-field">
		<label><?php _e('Description Image', 'woocommerce'); ?></label>
		<div id="description_cat_image" style="float:left;margin-right:10px;"><img src="<?php echo woocommerce_placeholder_img_src(); ?>" width="60px" height="60px" /></div>
		<div style="line-height:60px;">
			<input type="hidden" id="description_cat_image_id" name="description_cat_image_id" />
			<button type="submit" class="upload_desc_image_button button"><?php _e('Upload/Add image', 'woocommerce'); ?></button>
			<button type="submit" class="remove_desc_image_button button"><?php _e('Remove image', 'woocommerce'); ?></button>
		</div>
		<div class="clear"></div>
	</div>
	<script type="text/javascript">
			
		// Uploading files
		var file_desc_frame;

		jQuery(document).on( 'click', '.upload_desc_image_button', function( event ){

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( file_desc_frame ) {
				file_desc_frame.open();
				return;
			}

			// Create the media frame.
			file_desc_frame = wp.media.frames.downloadable_file = wp.media({
				title: '<?php _e( 'Choose an image', 'woocommerce' ); ?>',
				button: {
					text: '<?php _e( 'Use image', 'woocommerce' ); ?>',
				},
				multiple: false
			});

			// When an image is selected, run a callback.
			file_desc_frame.on( 'select', function() {
				attachment = file_desc_frame.state().get('selection').first().toJSON();

				jQuery('#description_cat_image_id').val( attachment.id );
				jQuery('#description_cat_image img').attr('src', attachment.url );
				jQuery('.remove_desc_image_button').show();
			});

			// Finally, open the modal.
			file_desc_frame.open();
		});

		jQuery(document).on( 'click', '.remove_desc_image_button', function( event ){
			jQuery('#description_cat_image img').attr('src', '<?php echo woocommerce_placeholder_img_src(); ?>');
			jQuery('#description_cat_image_id').val('');
			jQuery('.remove_desc_image_button').hide();
			return false;
		});

	</script>
	<?php
	}
	add_action( 'product_cat_add_form_fields', 'add_category_detailed_description_image',178 );
	
	/**
	 * Edit the meta for description image for category.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @return void
	 *
	 * based on woocommerce-admin-taxonomies.php
	 */
	function edit_category_detailed_description_image( $term, $taxonomy ) {
	global $woocommerce;
		
		$desc_image 			= '';
		$desc_thumbnail_id 	=  absint( get_woocommerce_term_meta( $term->term_id, '_woocommerce_cat_desc_image', true ) );

		if ($desc_thumbnail_id) :
			$desc_image = wp_get_attachment_url( $desc_thumbnail_id );
		else :
			$desc_image = woocommerce_placeholder_img_src();
		endif;
		print_r($term);
		print_r($term->term_id);
		print_r($desc_image);
		?>
		<tr id="description_cat_image_form" class="form-field">
			<th scope="row" valign="top"><label><?php _e('Description Image', 'woocommerce'); ?></label></th>
			<td>
				<div id="description_cat_image" style="float:left;margin-right:10px;"><img src="<?php echo $desc_image; ?>" width="60px" height="60px" /></div>
				<div style="line-height:60px;">
					<input type="hidden" id="description_cat_image_id" name="description_cat_image_id" value="<?php echo $desc_thumbnail_id; ?>" />
					<button type="submit" class="upload_desc_image_button button"><?php _e('Upload/Add image', 'woocommerce'); ?></button>
					<button type="submit" class="remove_desc_image_button button"><?php _e('Remove image', 'woocommerce'); ?></button>
				</div>
				<div class="clear"></div>
			</td>
		</tr>
		
		<script type="text/javascript">
			
			// Uploading files
			var file_desc_frame;

			jQuery(document).on( 'click', '.upload_desc_image_button', function( event ){

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( file_desc_frame ) {
					file_desc_frame.open();
					return;
				}

				// Create the media frame.
				file_desc_frame = wp.media.frames.downloadable_file = wp.media({
					title: '<?php _e( 'Choose an image', 'woocommerce' ); ?>',
					button: {
						text: '<?php _e( 'Use image', 'woocommerce' ); ?>',
					},
					multiple: false
				});

				// When an image is selected, run a callback.
				file_desc_frame.on( 'select', function() {
					attachment = file_desc_frame.state().get('selection').first().toJSON();

					jQuery('#description_cat_image_id').val( attachment.id );
					jQuery('#description_cat_image img').attr('src', attachment.url );
					jQuery('.remove_desc_image_button').show();
				});

				// Finally, open the modal.
				file_desc_frame.open();
			});

			jQuery(document).on( 'click', '.remove_desc_image_button', function( event ){
				jQuery('#description_cat_image img').attr('src', '<?php echo woocommerce_placeholder_img_src(); ?>');
				jQuery('#description_cat_image_id').val('');
				jQuery('.remove_desc_image_button').hide();
				return false;
			});

		</script>
		
		<?php
	}
	add_action( 'product_cat_edit_form_fields', 'edit_category_detailed_description_image', 178,2 );
		
	/**
	 * woocommerce_category_thumbnail_field_save function.
	 *
	 * @access public
	 * @param mixed $term_id Term ID being saved
	 * @param mixed $tt_id
	 * @param mixed $taxonomy Taxonomy of the term being saved
	 * @return void
	 */
	function category_detailed_description_image_save( $term_id, $tt_id, $taxonomy ) {
		if ( isset( $_POST['description_cat_image_id'] ) )
			update_woocommerce_term_meta( $term_id, '_woocommerce_cat_desc_image', $_POST['description_cat_image_id'] );
		}
	add_action( 'created_term', 'category_detailed_description_image_save', 12,3);
	add_action( 'edit_term', 'category_detailed_description_image_save', 12,3 );