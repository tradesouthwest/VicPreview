<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    vicpreview
 * @subpackage /inc
 * @author     Larry Judd <tradesouthwest@gmail.com>
 * 
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'vicpreview_add_options_page' ); 
add_action( 'admin_init', 'vicpreview_register_admin_options' ); 

// Sub-menu $parent_slug, $page_title, $menu_title, $cap, $menu_slug, $funx
function vicpreview_add_options_page() 
{
   add_submenu_page(
       'options-general.php',
        esc_html__( 'vicpreview', 'woocommerce' ),
        esc_html__( 'VIC Preview', 'woocommerce' ),
        'manage_options',
        'vicpreview',
        'vicpreview_options_page' 
    );
}   
 
/** a.) Register new settings
 *  $option_group (page), $option_name, $sanitize_callback
 *  --------
 ** b.) Add sections
 *  $id, $title, $callback, $page
 *  --------
 ** c.) Add fields 
 *  $id, $title, $callback, $page, $section, $args = array() 
 *  --------
 ** d.) Options Form Rendering. action="options.php"
 *
 */

// a.) register all settings groups
function vicpreview_register_admin_options() 
{
    //options pg
    register_setting( 'vicpreview_options', 'vicpreview_options' );
     

/**
 * b1.) options section
 */        
    add_settings_section(
        'vicpreview_options_section',
        esc_html__( 'Configuration and Settings', 'woocommerce' ),
        'vicpreview_options_section_cb',
        'vicpreview_options'
    ); 
            // c1.) settings 
    add_settings_field(
        'vicpreview_cstitle_field',
        esc_attr__('Label for Preview Field', 'vicpreview'),
        'vicpreview_cstitle_field_cb',
        'vicpreview_options',
        'vicpreview_options_section',
        array( 
            'type'         => 'text',
            'option_group' => 'vicpreview_options', 
            'name'         => 'vicpreview_cstitle_field',
            'value'        => 
            esc_attr( get_option( 'vicpreview_options' )['vicpreview_cstitle_field'] ),
            'description'  => esc_html__( 'Shows below the last field in checkout.', 'vicpreview' ),
            'tip'          => esc_html__( 'Also used in orders in admin', 'vicpreview' )
        )
    );
    // c2.) settings 
    add_settings_field(
        'vicpreview_csdescription_field',
        esc_attr__('Message Above Preview Image', 'vicpreview'),
        'vicpreview_csdescription_field_cb',
        'vicpreview_options',
        'vicpreview_options_section',
        array( 
            'type'         => 'text',
            'option_group' => 'vicpreview_options', 
            'name'         => 'vicpreview_csdescription_field',
            'value'        => 
            esc_attr( get_option( 'vicpreview_options' )['vicpreview_csdescription_field'] ),
            'description'  => esc_html__( 'Shows above the blank preview image', 'vicpreview' ),
             'tip'         => esc_html__( 'Try: Image is only an example', 'vicpreview' )
        )
    );
}
/** 
 * name for 'label' field
 * @since 1.0.0
 */
function vicpreview_cstitle_field_cb($args)
{  
   printf(
        '<input type="%1$s" name="%2$s[%3$s]" id="%2$s-%3$s" 
        value="%4$s" class="regular-text" /><b class="wntip" title="%6$s"> ? </b><br>
        <span class="wndspan">%5$s </span>',
        $args['type'],
        $args['option_group'],
        $args['name'],
        $args['value'],
        $args['description'],
        $args['tip']
    );
}

/** 
 * name for 'text' field
 * @since 1.0.0
 */
function vicpreview_csdescription_field_cb($args)
{  
   printf(
        '<input type="%1$s" name="%2$s[%3$s]" id="%2$s-%3$s" 
        value="%4$s" class="regular-text" /><b class="wntip" title="%6$s"> ? </b><br>
        <span class="wndspan">%5$s </span>',
        $args['type'],
        $args['option_group'],
        $args['name'],
        $args['value'],
        $args['description'],
        $args['tip']
    );
}

/**
 ** Section Callbacks
 *  $id, $title, $callback, $page
 */
// section heading cb
function vicpreview_options_section_cb()
{    
print( VICPREVIEW_VER );
} 


// d.) render admin page
function vicpreview_options_page() 
{
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) return;
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    ?>
    <div class="wrap wrap-vicpreview-admin">
    
    <h1><span id="SlwOptions" class="dashicons dashicons-admin-tools"></span> 
    <?php echo esc_html( 'vicpreview plugin Options' ); ?></h1>
         
    <form action="options.php" method="post">
    <?php //page=vicpreview&tab=vicpreview_options
        settings_fields(     'vicpreview_options' );
        do_settings_sections( 'vicpreview_options' ); 
        
        submit_button( 'Save Settings' ); 
 
    ?>
    </form>
    </div>
<?php 
} 