<?php
/*
Plugin Name: Xubio Woocommerce
Description: xubio integration with woocommerce.
Version: 1.0.0
Author: Saul Morales Pacheco
Author URI: http://saulmoralespa.com
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: xubio-woocommerce
Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; //Exit if accessed directly
}
if (!defined('XWSMP_XUBIO_WOOCOMMERCE_PLUGIN_VERSION')){
	define('XWSMP_XUBIO_WOOCOMMERCE_PLUGIN_VERSION', '1.0.0');
}
add_action('plugins_loaded', 'xwsmp_xubio_woocommerce_load');
function xwsmp_xubio_woocommerce_load(){
	load_plugin_textdomain( 'xubio-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	if(!requeriments_xwsmp_xubio_woocommerce()){
		return;
	}

	xwsmp_xubio_woocommerce()->xubio_run();
}
add_action('notices_action_xwsmp_xubio', 'xwsmp_xubio_woocommerce_notices', 10, 1);
function xwsmp_xubio_woocommerce_notices($notice){
	?>
	<div class="error notice">
		<p><?php echo $notice; ?></p>
	</div>
	<?php
}
function requeriments_xwsmp_xubio_woocommerce()
{
	if ( !in_array(
		'woocommerce/woocommerce.php',
		apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
		true
	) ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			$woo = __( 'Xubio: Woocommerce must be installed and active.', 'xubio-woocommerce' );
			do_action('notices_action_xwsmp_xubio', $woo);
		}
		return false;
	}

	if (version_compare(WC_VERSION, '3.0', '<')) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			$wc_version = __( 'Xubio: Version 3.0 or greater of installed woocommerce is required.', 'tigo-money-woo' );
			do_action('notices_action_xwsmp_xubio', $wc_version);
		}
		return false;
	}

	if ( version_compare( '5.6.0', PHP_VERSION, '>' ) ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			$php = __( 'Xubio: Requires php version 5.6.0 or higher.', 'xubio-woocommerce' );
			do_action('notices_action_xwsmp_xubio', $php);
		}
		return false;
	}
	if (!function_exists('curl_version')){
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			$curl = __( 'Xubio: Requires cURL extension to be installed.', 'xubio-woocommerce' );
			do_action('notices_action_xwsmp_xubio', $curl);
		}
		return false;
	}
	return true;
}

function xwsmp_xubio_woocommerce()
{
	static $plugin;
	if (!isset($plugin))
	{
		require_once('includes/class-xubio-plugin.php');
		$plugin = new XUBIO_PLUGIN(__FILE__,XWSMP_XUBIO_WOOCOMMERCE_PLUGIN_VERSION);
	}
	return $plugin;
}