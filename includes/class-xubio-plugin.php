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
        require_once ($this->includes_path . 'class-xubio-api-client.php');
        require_once ($this->includes_path . 'class-xubio-api-send-transaction-mail.php');
        require_once ($this->includes_path . 'class-xubio-api-receipt.php');
        require_once ($this->includes_path . 'class-xubio-api-proof-purchase.php');
        require_once ($this->includes_path . 'class-xubio-api-checkin.php');
        require_once ($this->includes_path . 'class-xubio-api-purchase-order.php');
        require_once ($this->includes_path . 'class-xubio-api-budget.php');
        require_once ($this->includes_path . 'class-xubio-api-provider.php');
        require_once ($this->includes_path . 'class-xubio-api-point-sale.php');
        require_once ($this->includes_path . 'class-xubio-api-cae.php');

        require_once ($this->includes_path . 'class-xubio-admin-tabs.php');
        $this->Admin = new Xubio_Admin();
        $this->AdminConfiguration = new Xubio_Admin_Configuration();
        $this->apiClient = new Xubio_Api_Client();
        $this->sendTransactionMail = new Xubio_Api_Send_Transaction_Mail();
        $this->receipt = new Xubio_Api_Receipt();
        $this->proofofpurchase = new Xubio_Api_Proof_Purchase();
        $this->checkin = new Xubio_Api_Checkin();
        $this->purchaseorder = new Xubio_Api_Purchase_Order();
        $this->budget = new Xubio_Api_Budget();
        $this->provider = new Xubio_Api_Provider();
        $this->pointSale = new Xubio_Api_Point_Sale();
        $this->cae = new Xubio_Api_Cae();
        $this->tabsMenu = new Xubio_Admin_Tabs();
    }


    public function createUrl($use = null)
    {
        $url = "https://xubio.com:443/API/1.1/";
        if (is_null($use)){
            $url .= "TokenEndpoint";
        }
        return $url;
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