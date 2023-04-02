<?php
/**
 * Plugin Name:       VIC Preview
 * Plugin URI:        https://themes.tradesouthwest.com/wordpress/plugins/
 * Description:       Preview WooCommerce Product Image.
 * Version:           1.1.0
 * Author:            Larry Judd
 * Author URI:        http://tradesouthwest.com
 * @package           vicpreview
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Requires Package:  WooCommerce 3.0+
 * Tested Package:    WooCommerce 3.7.1
 * Requires at least: 4.5
 * Tested up to:      6.2
 * Requires PHP:      5.4
 * Text Domain:       vicpreview
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !defined( 'VICPREVIEW_VER' ) )  { define( 'VICPREVIEW_VER', time() ); } 
//activate/deactivate hooks
function vicpreview_plugin_activation() 
{
    set_transient( 'vicpreview-admin-notice-dependancies', true, 5 );
}
// notice
add_action( 'admin_notices', 'vicpreview_admin_notice_dependancies' );
function vicpreview_admin_notice_dependancies(){

    /* Check transient, if available display notice */
    if( get_transient( 'vicpreview-admin-notice-dependancies' ) ){
        ?>
        <div class="updated notice is-dismissible">
            <p>VIC Preview plugin works best with <strong>File Uploads Addon for WooCommerce by Imaginate Solutions</strong>.</p>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'vicpreview-admin-notice-dependancies' );
    }
}
function vicpreview_plugin_deactivation() 
{
    return false;
}
/**
 * Plugin Scripts
 *
 * Register and Enqueues plugin scripts
 *
 * @since 1.0.0
 */
function vicpreview_addtosite_scripts()
{
    wp_enqueue_style( 'vicpreview-public',  
                    plugin_dir_url(__FILE__) . 'relate/vicpreview-public-style.css',
                    array(), VICPREVIEW_VER, false );
    wp_enqueue_script( 'vicpreview-front', 
                    plugin_dir_url( __FILE__ ) . 'relate/vicpreview-front.js', 
                    array( 'jquery' ), VICPREVIEW_VER, true ); 
}
add_action( 'wp_enqueue_scripts', 'vicpreview_addtosite_scripts' );

//load language scripts     
function vicpreview_load_text_domain() 
{
    load_plugin_textdomain( 'vicpreview', false, 
    basename( dirname( __FILE__ ) ) . '/languages' ); 
}

/**
 * Load all required files
 * @since 1.0.1
 */

register_activation_hook( __FILE__, 'vicpreview_plugin_activation');
register_deactivation_hook( __FILE__, 'vicpreview_plugin_deactivation');
require_once ( plugin_dir_path( __FILE__ ) . 'include/vicpreview-core.php'); 
require_once ( plugin_dir_path( __FILE__ ) . 'include/vicpreview-functions.php' );
?>