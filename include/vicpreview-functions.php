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
// A1
add_action( 'wp_footer', 'vicpreview_background_images_render' ); 
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
        '<article class="viewincrystal">
        <section class="vicpreview-above-cart">
            <p class="before-cart-upload"><small>' . __( $opta, 'vicpreview') 
            . '</small></p>
            <span class="vicpreview-description">' . __( $optb, 'vicpreview') 
            . '</span>
            <input id="vicpreview_blank" type="hidden" value="'. esc_attr($blnk).'" name="vicpreview_blank">
        </section>
        <section id="pfntCan" class="vicpreview-containerz">
            <div id="vicPreviewer-message"></div>';
    if ( $blnk ) {
    $htm .= 
        '<div id="preview-areaz">
        
            <ul>
            <li class="image_wrapper">
       
            <div id="crystalVic" class="vicpreview-background-blank">
                <img src="'. esc_url($blnk) .');" alt=""/>
                <div class="overlay overlay_3">
                <img id="vicpreview" class="vicpreview-above-addtocart" 
                src="" 
                alt="upload your image" />
                </div>
            </div>
            
            </li>
            </ul>
        </div>';
    }

    $htm .= 
        '</section>
        <div class="printdiv-instructions">
        <span class="btn btn-primary print">Print Preview</span>
        </div>
        </article>';
    
    $htm .= ob_get_clean();

        echo $htm;
} 

/**
 * @id A1
 * 
 * returns inline-'stylesheet' changes to the HTML layout. 
 */
function vicpreview_background_images_render( )
{   
?>  <script id="jquery-printme">
// jquery.printpage.js
// -------------------
// Adds a print page icon and onclick event handler
// to a <span class="print">...</span> tag
// 
(function(jQuery) {
    jQuery.fn.printPage = function() {
       return this.each(function() {
            // Wrap each element in a <a href="#">...</a> tag
            var $current = jQuery(this);
            $current.wrapInner('<a href="#"></a>');
            
            jQuery('span.print > a').click(function() {
                window.print(); 
                return false;    
            });
       });
    }
})(jQuery); </script> 
<script id="picview-footer">
jQuery(document).ready(function () {
		jQuery('span.print').printPage();
	});
</script>

<?php 
}

/**
 * @id A1
 * onclick="printDiv(\'pfntCan\')
 * returns inline-'stylesheet' changes to the HTML layout. 
 */

function vicpreview_background_images_render_old()
{   
ob_start();
?>
<script id="vicpreview-footer">
    const printButton = document.getElementById('print-button');
    const bkgndImg = document.getElementById('crystalVic').getAttribute("title");
    printButton.addEventListener('click', event => {
    // build the new HTML page
    const content = document.getElementById('preview-area').innerHTML;
    const printHtml = `<html>
        <head>
            <meta charset="utf-8">
            <title>Preview Crystal</title>
        </head>
        <body><img style="z-index:1;position:relative" src="${bkgndImg})"/>
            <div style="z-index:999;position:absolute;top:22%;left:20%;">${content}</div>
        </body>
    </html>`;
    // get the iframe
    let iFrame = document.getElementById('print-iframe');
  
    // set the iFrame contents and print
    //console.log('iFrame');
    //console.log(iFrame.contentDocument);
    iFrame.contentDocument.body.innerHTML = printHtml;
    iFrame.focus();
    iFrame.contentWindow.print();
});
</script>

<?php 
    echo ob_get_clean();
   
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
