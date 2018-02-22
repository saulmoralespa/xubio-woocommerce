<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 21/02/18
 * Time: 07:16 PM
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if (! class_exists('WC_XUBIO_INTEGRATION')):

    class WC_XUBIO_INTEGRATION extends WC_Integration
    {

        public function __construct()
        {
            global $woocommerce;

            $this->id                 = 'xubio';
            $this->method_title       = __( 'Xubio', 'xubio-woocommerce' );
            $this->method_description = __( 'The small business management solution', 'xubio-woocommerce' );
            $this->init_form_fields();
            $this->init_settings();
            $this->client_id = $this->get_option( 'client_id' );
            $this->client_secret = $this->get_option( 'client_secret' );
            $this->debug = $this->get_option( 'debug' );

            add_action( 'woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options' ) );
            add_filter( 'woocommerce_settings_api_sanitized_fields_' . $this->id, array( $this, 'sanitize_settings' ) );

        }

        public function init_form_fields()
        {
            $this->form_fields = array(
                'client_id' => array(
                    'title'             => __( 'Client id', 'xubio-woocommerce' ),
                    'type'              => 'text',
                    'description'       => __( 'client-id You get it when you create the Client app', 'xubio-woocommerce' ),
                    'desc_tip'          => true,
                    'default'           => ''
                ),
                'client_secret' => array(
                    'title'             => __( 'Client secret', 'xubio-woocommerce' ),
                    'type'              => 'text',
                    'description'       => __( 'client-secret You get it when you create the Client app', 'xubio-woocommerce' ),
                    'desc_tip'          => true,
                    'default'           => ''
                ),
                'debug' => array(
                    'title'             => __( 'Debug Log', 'xubio-woocommerce' ),
                    'type'              => 'checkbox',
                    'label'             => __( 'Enable logging', 'xubio-woocommerce' ),
                    'default'           => 'no',
                    'description'       => __( 'Log events such as API requests', 'xubio-woocommerce' ),
                    'desc_tip'          => true,
                ),
            );
        }

        public function sanitize_settings( $settings )
        {

            $params  = array(
                'client_id'       => $settings['client_id'],
                'client_secret' => $settings['client_secret'],
            );
            $this->test_xubio_woocommerce_token( $params );
            return $settings;
        }

        public function test_xubio_woocommerce_token($params)
        {
            $access = $params['client_id'] . ":" . $params['client_secret'];
            $access = base64_encode($access);
            $token = wp_safe_remote_post( $this->createUrl(), array('headers' => array( 'cache-control' => 'no-cache','content-type'  => 'application/x-www-form-urlencoded', 'authorization' => 'Basic '. $access ),'body' => array( 'grant_type' => 'client_credentials')));

            $error = false;
            if ( is_wp_error( $token ) ){
                $error = true;
            }
            if ( $token['response']['code'] != 200 ){
                $error = true;
            }
            if($error){
                do_action('notices_action_xwsmp_xubio', __('Failed to connect, check client_id and client_secret accesses','xubio-woocommerce'));
            }
        }

        public function createUrl($use = null)
        {
            $url = "https://xubio.com:443/API/1.1/";
            if (is_null($use)){
                $url .= "TokenEndpoint";
            }
            return $url;
        }
    }

    endif;