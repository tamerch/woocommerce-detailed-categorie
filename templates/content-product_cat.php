<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

get_header('shop'); 
global $plugin_dir;
$img_url = $plugin_dir."assets/images/" ;

?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action('woocommerce_before_main_content');
	?>
		<h1 class="page-title">
			<?php if ( is_search() ) : ?>
				<?php
					printf( __( 'Search Results: &ldquo;%s&rdquo;', 'woocommerce' ), get_search_query() );
					if ( get_query_var( 'paged' ) )
						printf( __( '&nbsp;&ndash; Page %s', 'woocommerce' ), get_query_var( 'paged' ) );
				?>
			<?php elseif ( is_tax() ) : ?>
				<?php echo single_term_title( "", false ); ?>
			<?php else : ?>
				<?php
					$shop_page = get_post( woocommerce_get_page_id( 'shop' ) );

					echo apply_filters( 'the_title', ( $shop_page_title = get_option( 'woocommerce_shop_page_title' ) ) ? $shop_page_title : $shop_page->post_title );
				?>
			<?php endif; ?>
		</h1>

		<?php do_action( 'woocommerce_archive_description' ); ?>
		
		<?php if ( is_tax() ) : ?>
			<?php do_action( 'woocommerce_taxonomy_archive_description' ); ?>
		<?php elseif ( ! empty( $shop_page ) && is_object( $shop_page ) ) : ?>
			<?php do_action( 'woocommerce_product_archive_description', $shop_page ); ?>
		<?php endif; ?>
		
		<?php global $post;
		
		
		$terms = wp_get_post_terms( $post->ID, 'product_cat' );
		$term = $terms[count($terms)-1];
		//print_r($terms);
		$term = $terms[1];
		$img_id = get_woocommerce_term_meta( $term->term_id, '_woocommerce_cat_desc_image', true );
		//print_r($img_id);
		
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$img_id = get_woocommerce_term_meta( $term->term_id, '_woocommerce_cat_desc_image', true );
		
		if ( $img_id ) {
			$attachement = wp_get_attachment_url( $img_id );
			echo '<div class="term-image">'; 
			echo '<img src="' . $attachement . '"/>';
			echo '</div>';
		}
		
		?>
		
		<?php if ( have_posts() ) : ?>

			<?php do_action('woocommerce_before_shop_loop'); ?>

				<?php woocommerce_product_subcategories(); ?>
				<table id="sortable" class="sortable parts" imgpath="<?php echo $img_url; ?>">
					<thead><tr><th></th><th>Ref.</th><th>Nom</th><th>Prix</th>
					<?php if (current_user_can('manage_options')) {?>
					<th>Qty</th><th>+</th></tr>
					<?php }?>
					</thead>
					
					<?php while ( have_posts() ) : the_post(); ?>
						<?php global $product//$product = new WC_Product(get_the_ID()); ?>
						<tr>
							<td class="thumbnails-table"><a href="<?php the_permalink(); ?>">
							<?php do_action( 'woocommerce_before_shop_loop_item_title' );?></a>
							</td>
							<td class="sku-table"><?php echo $product->sku; ?></td>
							<td class="title-table"><a href="<?php echo the_permalink(); ?>">
								<h3><?php the_title(); ?></h3>	
							</a></td>
							<td class="price-table">
							<a><?php do_action( 'woocommerce_after_shop_loop_item_title' );?></a>
							</td>
							<?php if (current_user_can('manage_options')) {?>
							<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="cart" method="post" enctype='multipart/form-data'>
							<td class="add-to-cart" width="60px">
								<?php
								if ( ! $product->is_sold_individually() )
									woocommerce_quantity_input( array(
										'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
										'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
									) );
								?>
							</td>
							<td width="32px">
								<button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type); ?></button>
							</td>
							</form>
							<?php }?>
						<?php //woocommerce_get_template_part( 'content', 'product' ); ?>
						</tr>
					<?php endwhile; // end of the loop. ?>
					
				</table>
			<?php do_action('woocommerce_after_shop_loop'); ?>

		<?php else : ?>

			<?php if ( ! woocommerce_product_subcategories( array( 'before' => '<ul class="products">', 'after' => '</ul>' ) ) ) : ?>

				<p><?php _e( 'No products found which match your selection.', 'woocommerce' ); ?></p>

			<?php endif; ?>

		<?php endif; ?>

		<div class="clear"></div>

		<?php
			/**
			 * woocommerce_pagination hook
			 *
			 * @hooked woocommerce_pagination - 10
			 * @hooked woocommerce_catalog_ordering - 20
			 */
			do_action( 'woocommerce_pagination' );
		?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action('woocommerce_sidebar');
	?>

<?php get_footer('shop'); ?>