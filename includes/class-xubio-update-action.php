<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 23/02/18
 * Time: 10:32 PM
 */

class Xubio_Update_Action
{
    public function __construct()
    {
        add_filter('woocommerce_billing_fields', array($this, 'custom_woocommerce_billing_fields'));
        add_action( 'woocommerce_order_status_completed', array($this, 'new_client_payment_complete'), 99, 1);
    }

    public function new_client_payment_complete($order_id )
    {
        $order = wc_get_order($order_id);
        $order_data = $order->get_data();

        $order_customer_id = $order_data['customer_id'];
        $order_billing_first_name = $order_data['billing']['first_name'];
        $order_billing_last_name = $order_data['billing']['last_name'];
        $order_billing_nit  = get_post_meta( $order_id, '_billing_nit', true );
        $order_billing_email =  $order_data['billing']['email'];
        $order_billing_phone =  $order_data['billing']['phone'];
        $order_billing_address =  $order_data['billing']['address_1'] . " " . $order_data['billing']['address_2'];
        $order_billing_postcode = empty($order_data['billing']['postcode'])  ? '00000' : $order_data['billing']['postcode'];
        $print = print_r($order_data, true);
        xwsmp_xubio_woocommerce()->log($print);

        $array = array('cliente_id' => $order_customer_id, 'nombre' => $order_billing_first_name, 'identificacionTributaria' => array('nombre' => 'string', 'codigo' => 'string', 'id' => 0), 'categoriaFiscal' => array('nombre' => $order_billing_first_name, 'codigo' => 'string', 'id' => 0), 'provincia' => array('nombre' => 'string', 'codigo' => 'string', 'id' => 0), 'direccion' => $order_billing_address, 'email' => $order_billing_email, 'telefono' => $order_billing_phone, 'razonSocial' => 'string', 'codigoPostal' => $order_billing_postcode, 'cuentaVenta_id' => array('nombre' => 'string', 'codigo' => 'string', 'id' => 0), 'cuentaCompra_id' => array('nombre' => 'string', 'codigo' => 'string', 'id' => 0), 'pais' => array('nombre' => 'string', 'codigo' => 'string', 'id' => 0), 'localidad' => array('nombre' => 'string', 'codigo' => 'string', 'id' => 0), 'usrCode' => 'strring', 'listaPrecioVenta' => array('nombre' => 'string', 'codigo' => 'string', 'id' => 0), 'descripcion' => 'string', 'cuit' => 'string');
        $json = json_encode($array);
        $token = xwsmp_xubio_woocommerce()->getToken();
        $create_client =  wp_safe_remote_post(xwsmp_xubio_woocommerce()->createUrl('client'),
            array('headers' => array('cache-control' => 'no-cache', 'content-type' => 'application/json','authorization' => 'Bearer '. $token ), 'body' => $json ));
        $create_client = wp_remote_retrieve_body( $create_client );
        $print = print_r($create_client, true);
        xwsmp_xubio_woocommerce()->log($print);

    }

    function custom_woocommerce_billing_fields($fields)
    {

        $fields['billing_nit'] = array(
            'label' => __('NIT, CC', 'xubio-woocommerce'),
            'placeholder' => _x('Identification number', 'placeholder', 'xubio-woocommerce'),
            'required' => false,
            'clear' => false,
            'type' => 'text',
            'class' => array('my-css')
        );
        return $fields;
    }

}