<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 21/02/18
 * Time: 06:21 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class XUBIO_PLUGIN
{
    /**
     * Filepath of main plugin file.
     *
     * @var string
     */
    public $file;
    /**
     * Plugin version.
     *
     * @var string
     */
    public $version;
    /**
     * Absolute plugin path.
     *
     * @var string
     */
    public $plugin_path;
    /**
     * Absolute plugin URL.
     *
     * @var string
     */
    public $plugin_url;
    /**
     * Absolute path to plugin includes dir.
     *
     * @var string
     */
    public $includes_path;
    /**
     * Flag to indicate the plugin has been boostrapped.
     *
     * @var bool
     */
    private $_bootstrapped = false;

    /**
     * @var
     */
    public $settings;
    /**
     * @var WC_Logger
     */
    public $logger;

    public function __construct($file, $version)
    {
        $this->file = $file;
        $this->version = $version;
        $this->plugin_path   = trailingslashit( plugin_dir_path( $this->file ) );
        $this->plugin_url    = trailingslashit( plugin_dir_url( $this->file ) );
        $this->includes_path = $this->plugin_path . trailingslashit( 'includes' );
        $this->logger = new WC_Logger();
    }

    public function xubio_run()
    {
        try{
            if ($this->_bootstrapped){
                throw new Exception( __( 'Xubio can only be called once', 'xubio-woocommerce' ));
            }
            $this->_run();
            $this->_bootstrapped = true;
        }catch (Exception $e){
            if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
                do_action('notices_action_xwsmp_xubio', $e->getMessage());
            }
        }
        add_filter( 'plugin_action_links_' . plugin_basename( $this->file ), array( $this, 'plugin_action_links' ) );
    }

    public function _run()
    {
        if ( class_exists( 'WC_Integration' ) ) {
            include_once ( $this->includes_path . 'class-xubio-integration.php' );
            add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
        }
    }

    public function plugin_action_links( $links ) {
        $plugin_links = array();
        if ( function_exists( 'WC' ) ) {
            $setting_url = 'https://google.com';
            $plugin_links[] = '<a href="' . esc_url( $setting_url ) . '">' . esc_html__( 'Settings', 'xubio-woocommerce' ) . '</a>';
        }
        $plugin_links[] = '<a href="https://docs.woocommerce.com/document/paypal-express-checkout/">' . esc_html__( 'Docs', 'xubio-woocommerce' ) . '</a>';
        return array_merge( $plugin_links, $links );
    }

    public function add_integration( $integrations ) {
        $integrations[] = 'WC_XUBIO_INTEGRATION';
        return $integrations;
    }
}