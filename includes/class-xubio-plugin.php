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

class Xubio_Plugin
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
     * @var string
     */
    public $name;
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

    public function __construct($file, $version, $name)
    {
        $this->file = $file;
        $this->version = $version;
        $this->name = $name;
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

    protected function _run()
    {
        require_once ($this->includes_path . 'class-xubio-admin.php');
        require_once ($this->includes_path . 'class-xubio-admin-configuration.php');
        require_once ($this->includes_path . 'class-xubio-api-client.php');;
        require_once ($this->includes_path . 'class-xubio-admin-tabs.php');

        $this->Admin = new Xubio_Admin();
        $this->AdminConfiguration = new Xubio_Admin_Configuration();
        $this->apiClient = new Xubio_Api_Client();
        $this->tabsMenu = new Xubio_Admin_Tabs();
    }


    public function createUrl($use = '')
    {
        $url = "https://xubio.com:443/API/1.1/";
        if (empty($use))
            $url .= "TokenEndpoint";

        if ($use == 'client')
            $url .= "clienteBean";
        return $url;
    }

    public function getToken($access = null)
    {
        if (is_null($access)){
            $client_id =  get_option('xwsmp-client-id-xubio-woo');
            $client_secret = get_option('xwsmp-client-secret-xubio-woo');
            $access = base64_encode( "$client_id:$client_secret" );
        }
        $token = wp_safe_remote_post( xwsmp_xubio_woocommerce()->createUrl(), array('headers' => array( 'cache-control' => 'no-cache','content-type'  => 'application/x-www-form-urlencoded', 'authorization' => 'Basic '. $access ),'body' => array( 'grant_type' => 'client_credentials')));

        $token = wp_remote_retrieve_body( $token );
        $token = json_decode($token);
        if (!isset($token->access_token))
            return null;
        return $token->access_token;
    }

    public function log($msg = '')
    {
        $upload_dir = wp_upload_dir();
        $file =  trailingslashit($upload_dir['basedir']) . 'xublio.txt';
        $handle = fopen($file, 'a+');
        fwrite($handle, $msg);
        fclose($handle);
    }

    public function plugin_action_links( $links ) {
        $plugin_links = array();
        if ( function_exists( 'WC' ) ) {
            $setting_url = 'https://google.com';
            $plugin_links[] = '<a href="' . esc_url( $setting_url ) . '">' . esc_html__( 'Settings', 'xubio-woocommerce' ) . '</a>';
        }
        $plugin_links[] = '<a href="https://docs.woocommerce.com/document/$this->Adminpaypal-express-checkout/">' . esc_html__( 'Docs', 'xubio-woocommerce' ) . '</a>';
        return array_merge( $plugin_links, $links );
    }
}