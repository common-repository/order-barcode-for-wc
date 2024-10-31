<?php
/*
 * Plugin Name:       Order Barcode for WC
 * Plugin URI:        https://wordpress.org/plugins/order-barcode-for-wc/
 * Description:       Order Barcode for WC, You can use on your woocommerce product with different product.
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            Sadekur Rahman
 * Author URI:        sadekurrahmansoikut.com
 * Version:           0.1.1
 * Text Domain:       wbwc
*/
require_once __DIR__ . '/vendor/autoload.php';

if( ! defined( 'ABSPATH' ) ) exit();
//ORDERBARCODE_VERSION
if ( ! defined( 'ORDERBWC_VERSION' ) ) {
    define( 'ORDERBWC_VERSION', '0.1.1' );
}
if ( ! defined( 'ORDERBWC_SUPPORT_LINK' ) ) {
    define( 'ORDERBWC_SUPPORT_LINK', esc_url('https://wordpress.org/support/plugin/order-barcode-for-wc') );
}
if ( ! defined( 'ORDERBWC_REVIEW_LINK' ) ) {
    define( 'ORDERBWC_REVIEW_LINK',esc_url('https://wordpress.org/plugins/order-barcode-for-wc/#reviews') );
}
if ( ! defined( 'ORDERBWC_PRO_LINK' ) ) {
    define( 'ORDERBWC_PRO_LINK',esc_url('https://sadekurrahmansoikut.com/order-barcode-wc-pro') );
}

// Minimum PHP Version
if ( ! defined( 'ORDERBWC_MINIMUM_PHP_VERSION' ) ) {
    define( 'ORDERBWC_MINIMUM_PHP_VERSION', '7.0');
}
if ( ! defined( 'ORDERBWC_API_ENDPOINT' ) ) {
    define( 'ORDERBWC_API_ENDPOINT',esc_url('https://bwipjs-api.metafloor.com/') );
}
if ( ! defined( 'ORDERBWC_PATH' ) ) { 
    define('ORDERBWC_PATH', plugin_dir_path(__FILE__));
}


define('ORDERBARCODE_CSS_URI', plugins_url( 'css/main.css', __FILE__));
define('ORDERBARCODE_CSS_ADMIN_URI', plugins_url ( 'css/adminmain.css', __FILE__));
//js
define('ORDERBARCODE_JS_URI', plugins_url ( 'js/main.js', __FILE__));
define('ORDERBARCODEE_JS_URI', plugins_url ( 'js/print.min.js', __FILE__));
define('ORDERBARCODEADMIN_JS_URI', plugins_url ( 'js/admin-main.js', __FILE__));
define('ORDERBARCODEE_IRISJS', plugins_url ( 'js/iris.min.js', __FILE__));
define('ORDERBARCODEE_CP_ACTIVE', plugins_url ( 'js/cp-active.js', __FILE__));

function wbwc_order_barcodes_init() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            add_action( 'admin_notices', 'wc_barcodes_woocommerce_deactivated' );
            return;
        }
    }
    add_action('plugins_loaded', 'wbwc_order_barcodes_init');
    function wc_barcodes_woocommerce_deactivated() {
        echo '<div class="error"><p>' . sprintf( esc_html__( 'WooCommerce Order Barcodes requires %s to be installed and active.', 'woocommerce-order-barcodes' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
    }

    function wbwc_plugin_row_meta( $links, $file ) 
    {
        if (strpos(plugin_dir_path(__FILE__),plugin_dir_path($file))) {
            $row_meta = array($links[0],$links[1]);
            $row_meta['Support']='<a target="_blank" href="'.ORDERBWC_SUPPORT_LINK.'">'.esc_html__('Support','orderbarcode').'</a>';
            $row_meta['Getpro']='<a target="_blank" class="get-pro" href="'.ORDERBWC_PRO_LINK.'">'.esc_html__('Get Pro','orderbarcode').'</a>';
            $row_meta['RateUs']='<a target="_blank" href="'.ORDERBWC_REVIEW_LINK.'"><span class="dashicons dashicons-star-filled stdi-rate"></span>'.esc_html__('Rate Us','orderbarcode').'</a>';
            
            return $row_meta;
        }
        return (array) $links;
    }//end of function
    add_filter( 'plugin_row_meta', 'wbwc_plugin_row_meta' , 10, 2 );

foreach ( glob( ORDERBWC_PATH."inc/*.php" ) as $php_file )
    include_once $php_file;
