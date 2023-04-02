<?php 
/**
 * @package    vicpreview-plus
 * @subpackage include/vicpreview-postmeta.php
 * @since      2.0.1
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * *****************************************
 * Post meta and Cart meta
 * **************************************** */
// TA1
add_action( 'wp', 'remove_image_zoom_support', 100 );
// PM-1 Add Number Field to admin General tab
add_action( 'woocommerce_product_options_inventory_product_data', 'vicpreview_general_product_data_field' );
// PM-2 Save meta 
add_action( 'woocommerce_process_product_meta', 'vicpreview_save_vicpreview_blank' );
// @id A1
//add_action( 'wp_footer', 'vicpreview_background_images_render' ); 
// A2
add_action( 'woocommerce_before_add_to_cart_button', 'vicpreview_before_add_to_cart_btn' );
// F1
//add_filter( 'woocommerce_add_cart_item_data', 'vicpreview_add_blank_cart_item_data', 10, 3);
// F2
//add_filter( 'woocommerce_get_item_data', 'vicpreview_get_item_data', 10, 2 );
// F3
//add_filter( 'woocommerce_product_single_add_to_cart_text', 'vicpreview_add_to_cart_button_text_single' ); 

/** TA1
 * TODO
 */
function remove_image_zoom_support() {
    remove_theme_support( 'wc-product-gallery-zoom' );
}


/** PM-1
 * Add fee field to product editor
 * 
 * @param         array
 * @param string $options
 */
function vicpreview_general_product_data_field() 
{   
    woocommerce_wp_text_input( array( 
        'id'       => '_vicpreview_blank', 
        'name'      => '_vicpreview_blank', 
        'class'      => 'vicpreview-blank',
        'placeholder' => '', 
        'label'       => __( 'Preview Blank', 'vicpreview' ), 
        'description' => __( '<br>url of blank crystal for ViewInCrystal image', 'vicpreview' ), 
        'type'        => 'url'
    ) );
}

/** PM-2
 * Save the whales... I mean save post meta
 * Hook callback functions to save custom fields 
 *
 * @param meta_id[int] 
 * @param post_id[int] 
 * @param meta_key[_vicpreview_blank] 
 * @param meta_value[int]
 * @since 2.0.1
 */

function vicpreview_save_vicpreview_blank( $post_id ) 
{
//global $product;

    $custom_field_value = isset( $_POST['_vicpreview_blank'] ) 
                               ? $_POST['_vicpreview_blank'] : '';
    $custom_field_clean = sanitize_text_field( $custom_field_value );
    $product = wc_get_product( $post_id );
    $product->update_meta_data( '_vicpreview_blank', $custom_field_clean );
    $product->save();
}
/** A2
 * Print notice above AddToCart button
 *
 * @see https://rudrastyh.com/woocommerce/before-and-after-add-to-cart.html
 * @param category Checks if product not in array
 * @return HTML Img isnt uploaded until the form is submitted so jquery Object creates blob
 */

function vicpreview_before_add_to_cart_btn()
{
	if( has_term( array( 'crystal-base' ), 'product_cat' ) ) return;
    global $woocommerce;

    $blnk = get_post_meta( get_the_id(), '_vicpreview_blank', true );
    $opta = (empty(get_option('vicpreview_options')['vicpreview_cstitle_field']))
            ? '' : get_option('vicpreview_options')['vicpreview_cstitle_field'];
    $optb = (empty(get_option('vicpreview_options')['vicpreview_csdescription_field']))
            ? '' : get_option('vicpreview_options')['vicpreview_csdescription_field'];
    
    ob_start();
    $htm = ''; 
    $htm .= 
        '<section class="vicpreview-above-cart">
            <p class="before-cart-upload"><small>' . __( $opta, 'vicpreview') 
            . '</small></p>
            <span class="vicpreview-description">' . __( $optb, 'vicpreview') 
            . '</span>
            <input id="vicpreview_blank" type="hidden" value="'. esc_attr($blnk).'" name="vicpreview_blank">
        </section>
            <div class="clearfix"></div><div id="vicPreviewer-message"></div>
        <section class="vicpreview-container">';
    
    if ( $blnk ) {
        $htm .= '
        <div class="vicpreview-background-blank" 
            style="background-image: url('. esc_url($blnk) .');">
            <img class="vicpreview-above-addtocart" src="" alt="" />
        </div>';
    }

    $htm .= '</section><div class="clearfix"></div>';
    
    $htm .= ob_get_clean();

        echo $htm;
} 

/** F3 
 * Change add to cart text on single product page
 *
 */

function vicpreview_add_to_cart_button_text_single() {
    $opts = (empty(get_option('vicpreview_options')['vicpreview_csdescription_field']))
            ? 'Preiview In Crystal' 
            : get_option('vicpreview_options')['vicpreview_csdescription_field'];
    return __( $opts, 'vicpreview' ); 
}


/** F1
 * Add custom cart item data
 */
function vicpreview_add_blank_cart_item_data( $cart_item_data, $product_id, $variation_id ) 
{
    if( isset( $_POST['vicpreview_blank'] ) ) {
        $cart_item_data['vicpreview_blank'] = sanitize_text_field( 
            $_POST['vicpreview_blank'] );
    }
 
        return $cart_item_data;
}

/** F2
 * Display custom item data in the cart
 * @uses woocommerce_get_item_data
 */
function vicpreview_get_item_data( $item_data, $cart_item_data ) 
{
    if( isset( $cart_item_data['vicpreview_blank'] ) ) {
    
        $item_data[] = array(
           'key' => __( 'Preview In Crystal', 'vicpreview' ),
           'value' =>  wc_clean( $cart_item_data['vicpreview_blank'] )
        );
    }
    
        return $item_data;
}

/**
 * @id A1
 * 
 * returns inline-'stylesheet' changes to the HTML layout. 
 */
function vicpreview_background_images_render( )
{   
    if ( !is_cart() ) return;

    $htm = ''; 
    $htm .= 'vicpreview-background-blank{display:block;width: 320px;height:340px;padding:5px;border:1px solid #ddd;
    background-size:contain;background-repeat:no-repeat;background-position:center;}
    .woocommerce-page img.vicpreview-above-addtocart{max-height:320px;}';
        wp_register_style( 'vicpreview-entry-set', false );
        wp_enqueue_style(   'vicpreview-entry-set' );
        wp_add_inline_style( 'vicpreview-entry-set', $htm );
}
