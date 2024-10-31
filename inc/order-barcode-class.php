<?php
/*	
* Class
*/
class Order_Barcode_for_WC {
    public function __construct(){
        add_action('wp_enqueue_scripts', array( $this, 'check_script_front'));
        add_action( 'admin_enqueue_scripts', array($this, 'check_script_admin' ));
        add_action('admin_menu', array($this, 'check_custom_menu'));
        add_action( 'wp_ajax_actions-value', array($this,'save_form_values_callback'));
        add_action('woocommerce_order_details_after_order_table', array( $this, 'woobar_custom_field_display_order_details'));
        add_action('woocommerce_admin_order_data_after_shipping_address', array($this,'obwo_checkout_field_display_admin_order_meta'), 10, 1 );
        add_action('woocommerce_before_thankyou', array( $this, 'woobar_woocommerce_before_thankyou'));
        add_action( 'woocommerce_email_customer_details', array($this,'obwo_add_email_order_meta'), 10, 3 ); 
    }
    public function check_script_admin(){
        wp_enqueue_style( 'admincss', ORDERBARCODE_CSS_ADMIN_URI, '', time(), false );
        wp_enqueue_script( 'iris', ORDERBARCODEE_IRISJS, '', time(), true );
        wp_enqueue_script( 'cp-active', ORDERBARCODEE_CP_ACTIVE, '', time(), true );
        wp_enqueue_script( 'check-js', ORDERBARCODEADMIN_JS_URI, '', time(), true );
        wp_localize_script( 'check-js', 'ORDERBARCODEADMIN', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );
    }
    public function check_script_front(){
        wp_enqueue_style( 'maincss', ORDERBARCODE_CSS_URI, '', time(), false );
        wp_enqueue_script( 'print-js', ORDERBARCODEE_JS_URI, '', time(), true );
        wp_enqueue_script( 'mainjs', ORDERBARCODE_JS_URI, '', time(), true );
        wp_localize_script('mainjs','ADORDERBARCODE',['ajax_url' => admin_url('admin-ajax.php')]);
    }

    public function check_custom_menu() {
        add_menu_page(
            'Barcode Setting',
            'Barcode Setting',
            'manage_options',
            'barcode-setting',
            array($this,'check_barcode_callback'),
            'dashicons-admin-generic',
            6
        );
        add_submenu_page(
            'barcode-setting',
            'Barcode Setting',
            'Barcode Setting',
            'manage_options',
            'barcode-setting',
            array($this,'check_barcode_callback')
        );
    }

    public function check_barcode_callback(){
        //$this->get_type   = get_option('qr_codes', true);
        $this->get_color  = get_option('qr_color', false);
        ?>
        <div class="wrap woocommerce customs">
            <form method="POST" id="mainforms" action="post">
                <input type="hidden" name="action" value="actions-value">
                <h2><?php _e('Order Barcodes', 'wbwc') ?></h2>
                <table class="form-table">
                    <tbody>
                        <!-- <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="wc_order_barcodes_type"><?php _e('Barcode Type', 'wbwc') ?><span class="woocommerce-help-tip"></span></label>
                            </th>
                            <td class="forminp forminp-select">
                                <select name="barcodes_type" id="wc_order_barcodes_type" style="min-width:350px;" class="wc-enhanced-select select2-hidden-accessible enhanced" tabindex="-1" aria-hidden="true">
                                    <option value="Code 39"><?php echo esc_attr($type_options); ?></option>
                                    <option value="QR Code">QR Code</option>
                                </select>                           
                            </td>
                        </tr> -->
                        <tr valign="top" class="wc_order_barcodes_colours">
                            <th scope="row" class="titledesc"><?php _e('Barcode Colors','wbwc') ?></th>
                            <td class="forminp">
                                <div class="color_box ">
                                    <input name="barcod_colours" id="barcod_colours" type="text" value="<?php echo esc_attr($this->get_color); ?>" class="color-picker">
                                </div></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <div class="successMessage" style="display:none;"><font color="green"><b><?php _e('Your colors change successfully.:)', 'wbwc') ?></b></font></div>
                        <button name="save" class="button-primary woocommerce-save-button custom-btn" type="submit" value="Save changes"><?php _e('Save changes', 'wbwc') ?></button></p>
                    </form>
                    <div class="grid-view">
                        <h2>
                            <?php
                            //$get_type   = get_option('qr_codes', true);
                            $get_color  = get_option('qr_color', true);
                            $slice      = $get_color;
                            $main_color = substr($slice, 1 );
                            $api        = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&color='.$main_color.'&text-demo&data=product';
                            $url        = add_query_arg(
                                [
                                    'bcid'          =>'code128',
                                    'text'          =>' ',                    
                                    'alttext'       =>'demo',
                                    'height'        =>10,
                                    'width'         =>20,
                                    'barcolor'      =>$main_color,
                                    'textcolor'     =>$main_color,
                                    'scale'         =>3,   
                                    'includetext'   =>false,          
                                    'textalign'    =>'center', 
                                ], ORDERBWC_API_ENDPOINT
                            );

                            ?>
                            <div class="worder-woo">
                                <img src="<?php echo esc_url($url); ?>" alt="">
                            </div>
                            <?php
                            ?> 
                        </h2>
                    </div>
                </div>
            <?php
        }
        public function save_form_values_callback() {
            //$this->barcodes_type = sanitize_text_field( $_POST['barcodes_type'] );
            $this->barcod_colours = sanitize_text_field( $_POST['barcod_colours'] );
            //update_option('qr_codes', $this->barcodes_type);
            update_option('qr_color', $this->barcod_colours);
            wp_send_json_success();
        }
        public function woobar_custom_field_display_order_details($order)
        {
            //$get_type   = get_option('qr_codes', true);
            $this->get_color  = get_option('qr_color', true);
            $this->slice      = $this->get_color;
            $this->main_color = substr($this->slice, 1 );
            $this->order_id   ="Your Order Id-".$order->get_id();
            //$api = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&text-'.$order_id.'&data=product no-'.$order_id;
            $url = add_query_arg(
                [
                    'bcid'          =>'code128',
                    'text'          =>$order->get_id(),                    
                    'alttext'       =>$this->order_id,
                    'height'        =>10,
                    'width'         =>20,
                    'barcolor'      =>$this->main_color,
                    'textcolor'     =>$this->main_color,
                    'scale'         =>3,   
                    'includetext'   =>true,
                ], ORDERBWC_API_ENDPOINT
            );?>
            <div class="worder-woo">
                <img src="<?php echo esc_url($url); ?>" alt="">
            </div>
            <?php
        }
        public function obwo_checkout_field_display_admin_order_meta($order)
        {
            $this->get_color  = get_option('qr_color', true);
            $this->slice      = $this->get_color;
            $this->main_color = substr($this->slice, 1 );
            $this->order_id   ="Your Order Id-".$order->get_id();
            //$api = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&text-'.$order_id.'&data=product no-'.$order_id;
            $url = add_query_arg(
                [
                    'bcid'          =>'code128',
                    'text'          =>$order->get_id(),                    
                    'alttext'       =>$this->order_id,
                    'height'        =>10,
                    'width'         =>20,
                    'barcolor'      =>$this->main_color,
                    'textcolor'     =>$this->main_color,
                    'scale'         =>3,   
                    'includetext'   =>true,          
                    //'textalign'     =>'center',         
                ], ORDERBWC_API_ENDPOINT
            );

            ?>
            <div class="worder-woo">
                <img src="<?php echo esc_url($url); ?>" alt="">
            </div>
        </div>
            <?php
        }
        public function obwo_add_email_order_meta($order, $sent_to_admin, $plain_text)
        {
            if ($plain_text===false) {
                $this->get_color  = get_option('qr_color', true);
                $this->slice      = $this->get_color;
                $this->main_color = substr($this->slice, 1 );
                $this->order_id   ="Your Order Id-".$order->get_id();
                //$api = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&text-'.$order_id.'&data=product no-'.$order_id;
                $url = add_query_arg(
                    [
                        'bcid'          =>'code128',
                        'text'          =>$order->get_id(),                    
                        'alttext'       =>$this->order_id,
                        'height'        =>10,
                        'width'         =>20,
                        'barcolor'      =>$this->main_color,
                        'textcolor'     =>$this->main_color,
                        'scale'         =>3,   
                        'includetext'   =>true,          
                        'textalign'     =>'center', 
                    ], ORDERBWC_API_ENDPOINT
                );

                ?>
                <div class="worder-woo">
                    <img src="<?php echo esc_url($url);?>" alt="">
                </div>
                <?php
            }
        }
        public function woobar_woocommerce_before_thankyou(){?>
            <div class="prints">
                <a href="" class="print fa fa-print" ><strong><?php _e('Print', 'wbwc') ?></strong></a> 
            </div>
            <?php
        }
        // QR Codes
        /*public function settings_fields () {
            $type_options = array(
                'code39'        => __( 'Code 39', 'woocommerce-order-barcodes' ),
                'code93'        => __( 'Code 93', 'woocommerce-order-barcodes' ),
                'code128'       => __( 'Code 128', 'woocommerce-order-barcodes' ),
                'datamatrix'    => __( 'Data Matrix', 'woocommerce-order-barcodes' ),
                'qr'            => __( 'QR Code', 'woocommerce-order-barcodes' ),
            );
        }*/
}
register_activation_hook( __FILE__, 'my_plugin_activate' );
new Order_Barcode_for_WC();