<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 22/02/18
 * Time: 02:12 PM
 */

class Xubio_Admin
{
    public function __construct()
    {
        $this->name = xwsmp_xubio_woocommerce()->name;
        $this->plugin_url = xwsmp_xubio_woocommerce()->plugin_url;
        $this->version = xwsmp_xubio_woocommerce()->version;
        add_action('admin_menu', array($this, 'loadMenuXubio'));
        add_action('wp_ajax_xubiowoo',array($this,'xubio_woo_ajax'));
    }

    public function loadMenuXubio()
    {
        $configuracion = xwsmp_xubio_woocommerce()->AdminConfiguration;
        $client = xwsmp_xubio_woocommerce()->apiClient;
        $sendTransactionMail = xwsmp_xubio_woocommerce()->sendTransactionMail;
        $receipt = xwsmp_xubio_woocommerce()->receipt;
        $proofofpurchase = xwsmp_xubio_woocommerce()->proofofpurchase;
        $checkin = xwsmp_xubio_woocommerce()->checkin;
        $purchaseorder = xwsmp_xubio_woocommerce()->purchaseorder;
        $budget = xwsmp_xubio_woocommerce()->budget;
        $provider = xwsmp_xubio_woocommerce()->provider;
        $pointofsale = xwsmp_xubio_woocommerce()->pointSale;
        $cae = xwsmp_xubio_woocommerce()->cae;
        add_menu_page($this->name, $this->name, 'manage_options', 'menus'. $this->name, array($this,'menu'. $this->name), $this->plugin_url .'icon.png');
        $config = add_submenu_page('menus' . $this->name, __('Configuration','xubio-woocommerce'), __('Configuration','xubio-woocommerce'), 'manage_options', 'config-' . $this->name,array($configuracion,'configInit'));
        $client = add_submenu_page('menus' . $this->name, __('Client','xubio-woocommerce'), __('Client','xubio-woocommerce'), 'manage_options', 'configclient-' . $this->name,array($client,'configInit'));
        $transactionMail = add_submenu_page('menus' . $this->name, __('Send transaction by email', 'xubio-woocommerce'), __('Send transaction by email', 'xubio-woocommerce'), 'manage_options', 'configtransactionmail-' . $this->name,array($sendTransactionMail,'configInit'));
        $receiptPurchase = add_submenu_page('menus' . $this->name, __('Receipt', 'xubio-woocommerce'), __('Receipt', 'xubio-woocommerce'), 'manage_options', 'configreceipt-' . $this->name,array($receipt,'configInit'));
        $proofpurchase = add_submenu_page('menus' . $this->name, __('Proof of purchase', 'xubio-woocommerce'), __('Proof of purchase', 'xubio-woocommerce'), 'manage_options', 'configproofofpurchase-' . $this->name,array($proofofpurchase,'configInit'));
        $check = add_submenu_page('menus' . $this->name, __('Check in', 'xubio-woocommerce'), __('Check in', 'xubio-woocommerce'), 'manage_options', 'configcheckin-' . $this->name,array($checkin,'configInit'));
        $purchase = add_submenu_page('menus' . $this->name, __('Purchase order', 'xubio-woocommerce'), __('Purchase order', 'xubio-woocommerce'), 'manage_options', 'configpurchaseorder-' . $this->name,array($purchaseorder,'configInit'));
        $budgetof = add_submenu_page('menus' . $this->name, __("Budget", 'xubio-woocommerce'), __("Budget", 'xubio-woocommerce'), 'manage_options', 'configbudget-' . $this->name,array($budget,'configInit'));
        $providerof = add_submenu_page('menus' . $this->name, __('Provider', 'xubio-woocommerce'), __('Provider', 'xubio-woocommerce'), 'manage_options', 'configprovider-' . $this->name,array($provider,'configInit'));
        $pointsale = add_submenu_page('menus' . $this->name, __('Point of sale', 'xubio-woocommerce'), __('Point of sale', 'xubio-woocommerce'), 'manage_options', 'configpointofsale-' . $this->name,array($pointofsale,'configInit'));
        $cae = add_submenu_page('menus' . $this->name, __('CAE', 'xubio-woocommerce'), __('CAE', 'xubio-woocommerce'), 'manage_options', 'configcae-' . $this->name,array($cae,'configInit'));
        remove_submenu_page('menus'. $this->name, 'menus'.$this->name);
        add_action('admin_head', array($this,'head_menu'));
        add_action('admin_footer', array($this,'footer_menu'));
    }

    public function head_menu()
    {
        wp_enqueue_style('admin-css-xubio-woo', $this->plugin_url."assets/css/materialize.css", array(), $this->version, null);
        wp_enqueue_style('admin-css-overlay-xubio-woo', $this->plugin_url."assets/css/xubio.css", array(), $this->version, null);
    }

    public function footer_menu()
    {
        wp_enqueue_script('admin-js-xubio-woo', $this->plugin_url."assets/js/config.js", array('jquery'), $this->version, true);
        wp_localize_script( 'admin-js-xubio-woo', 'xubiowoo', array(
            'loadcredentials' => __('Making test connection...','xubio-woocommerce'),
            'loadSuccessCredentials' => __('Successful connection','xubio-woocommerce'),
            'loadFailCredentials' => __('Error: verify the credentials entered','xubio-woocommerce'),
        ) );
    }

    public function xubio_woo_ajax()
    {
        if (isset($_POST['client_id'])){
            $client_id = trim($_POST['client_id']);
            $client_secret = trim($_POST['client_secret']);
            $access = base64_encode( $client_id . ":" . $client_secret );
            $token = wp_safe_remote_post( xwsmp_xubio_woocommerce()->createUrl(), array('headers' => array( 'cache-control' => 'no-cache','content-type'  => 'application/x-www-form-urlencoded', 'authorization' => 'Basic '. $access ),'body' => array( 'grant_type' => 'client_credentials')));
            $status = true;
            if ( is_wp_error( $token ) ){
                $status = false;
            }
            if ( $token['response']['code'] != 200 ){
                $status = false;
            }

            if ($status){
                update_option('xwsmp-client-id-xubio-woo',$client_id);
                update_option('xwsmp-client-secret-xubio-woo',$client_secret);
            }

            echo json_encode(array('status' => $status));

        }
        wp_die();
    }
}