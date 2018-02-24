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
        add_menu_page($this->name, $this->name, 'manage_options', 'menus'. $this->name, array($this,'menu'. $this->name), $this->plugin_url .'icon.png');
        $config = add_submenu_page('menus' . $this->name, __('Configuration','xubio-woocommerce'), __('Configuration','xubio-woocommerce'), 'manage_options', 'config-' . $this->name,array($configuracion,'configInit'));
        $client = add_submenu_page('menus' . $this->name, __('Client','xubio-woocommerce'), __('Client','xubio-woocommerce'), 'manage_options', 'configclient-' . $this->name,array($client,'configInit'));
        remove_submenu_page('menus'. $this->name, 'menus'.$this->name);

        add_action( 'admin_print_styles-' . $config, array($this, 'admin_custom_css' ));
        add_action( 'admin_print_scripts-' . $config, array($this, 'admin_custom_js' ));

        add_action( 'admin_print_styles-' . $client, array($this, 'admin_custom_css' ));
        add_action( 'admin_print_scripts-' . $client, array($this, 'admin_custom_js' ));
    }

    public function admin_custom_css()
    {
        wp_enqueue_style('admin-css-xubio-woo', $this->plugin_url."assets/css/materialize.css", array(), $this->version, null);
        wp_enqueue_style('admin-css-overlay-xubio-woo', $this->plugin_url."assets/css/xubio.css", array(), $this->version, null);
    }

    public function admin_custom_js()
    {
        wp_enqueue_script('admin-js-xubio-woo', $this->plugin_url."assets/js/config.js", array('jquery'), $this->version, true);
        wp_localize_script( 'admin-js-xubio-woo', 'xubiowoo', array(
            'loadcredentials' => __('Making test connection...','xubio-woocommerce'),
            'loadSuccessCredentials' => __('Successful connection','xubio-woocommerce'),
            'loadFailCredentials' => __('Error: verify the credentials entered','xubio-woocommerce'),
            'loadGetClients' => __('Consulting list of clients','xubio-woocommerce'),
        ) );
    }

    public function xubio_woo_ajax()
    {
        if (isset($_POST['client_id'])){
            $client_id = trim($_POST['client_id']);
            $client_secret = trim($_POST['client_secret']);
            $access = base64_encode( $client_id . ":" . $client_secret );
            $token = xwsmp_xubio_woocommerce()->getToken($access);

            $status = true;
            if (isset($token)){
                update_option('xwsmp-client-id-xubio-woo',$client_id);
                update_option('xwsmp-client-secret-xubio-woo',$client_secret);
            }else{
                $status = false;
            }
            echo json_encode(array('status' => $status));

        }
        if (isset($_POST['clients'])){
            $clients = $_POST['clients'];
            if ($clients == 'get'){
                $token = xwsmp_xubio_woocommerce()->getToken();
                $lists = wp_safe_remote_get( xwsmp_xubio_woocommerce()->createUrl('client'), array('headers' => array('cache-control' => 'no-cache', 'content-type' => 'application/json','authorization' => 'Bearer '. $token ) ));
                $lists = wp_remote_retrieve_body( $lists );
                $clients = json_decode($lists);
                if (isset($clients->error))
                    wp_die('');
                echo $lists;
            }
        }
        wp_die();
    }
}