<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $dynamo_tpl;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
?>
<li <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>" class="wc-product-overlay" data-viewtext="<?php echo __('View', 'dp-theme'); ?>">

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>

		<h3><?php the_title(); ?></h3>
		
		<?php
		// Short description on catalog pages
		if(get_option($dynamo_tpl->name . '_woocommerce_short_description', 'Y') == 'Y') : 
			$count = get_option($dynamo_tpl->name . '_woocommerce_short_desc_count', '');
			if(trim($count) == '') {
				$count = 50;
			}
			echo wc_short_description($count);	
		endif;
		?>

		<span class="dp-wc-price"><?php do_action( 'woocommerce_after_shop_loop_item_price' ); ?></span>
		<span class="dp-wc-rating"><?php do_action( 'woocommerce_after_shop_loop_item_rating' );?></span>
			
        </a>
	
<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
</li>