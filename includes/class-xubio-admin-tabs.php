<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 22/02/18
 * Time: 12:37 PM
 */

class Xubio_Admin_Tabs
{
    public function __construct()
    {
        $this->name = xwsmp_xubio_woocommerce()->name;
    }

    public function page()
    {
        if ($_GET['page'] == "config-$this->name") {
            $this->tab = 'general';
        }elseif ($_GET['page'] == "configclient-$this->name") {
            $this->tab = 'client';
        }elseif($_GET['page'] == "configtransactionmail-$this->name") {
            $this->tab = 'sendtransactionmail';
        }elseif($_GET['page'] == "configreceipt-$this->name") {
            $this->tab = 'receipt';
        }elseif($_GET['page'] == "configproofofpurchase-$this->name") {
            $this->tab = 'proofofpurchase';
        }elseif($_GET['page'] == "configcheckin-$this->name") {
            $this->tab = 'checkin';
        }elseif($_GET['page'] == "configpurchaseorder-$this->name") {
            $this->tab = 'purchaseorder';
        }elseif($_GET['page'] == "configbudget-$this->name") {
            $this->tab = 'budget';
        }elseif($_GET['page'] == "configprovider-$this->name") {
            $this->tab = 'provider';
        }elseif($_GET['page'] == "configpointofsale-$this->name") {
            $this->tab = 'pointofsale';
        }elseif($_GET['page'] == "configcae-$this->name") {
            $this->tab = 'cae';
        }

        $this->page_tabs($this->tab);

        if($this->tab == 'general' ) {
            $config = xwsmp_xubio_woocommerce()->AdminConfiguration;
            $config->content();
        }
        if($this->tab == 'client') {
            $client = xwsmp_xubio_woocommerce()->apiClient;
            $client->content();
        }
        if ($this->tab == 'sendtransactionmail') {
            $send = xwsmp_xubio_woocommerce()->sendTransactionMail;
            $send->content();
        }
        if ($this->tab == 'receipt') {
            $receipt = xwsmp_xubio_woocommerce()->receipt;
            $receipt->content();
        }
        if ($this->tab == 'proofofpurchase') {
            $proofofpurchase = xwsmp_xubio_woocommerce()->proofofpurchase;
            $proofofpurchase->content();
        }
        if ($this->tab == 'checkin') {
            $checkin = xwsmp_xubio_woocommerce()->checkin;
            $checkin->content();
        }
        if ($this->tab == 'purchaseorder') {
            $purchaseorder = xwsmp_xubio_woocommerce()->purchaseorder;
            $purchaseorder->content();
        }
        if ($this->tab == 'budget') {
            $budget = xwsmp_xubio_woocommerce()->budget;
            $budget->content();
        }
        if ($this->tab == 'provider') {
            $provider = xwsmp_xubio_woocommerce()->provider;
            $provider->content();
        }
        if ($this->tab == 'pointofsale') {
            $pointofsale = xwsmp_xubio_woocommerce()->pointSale;
            $pointofsale->content();
        }
        if ($this->tab == 'cae') {
            $cae = xwsmp_xubio_woocommerce()->cae;
            $cae->content();
        }
    }

    public function page_tabs($current = 'general')
    {
        
        $tabs = array(
            'general'   => array('config-' . $this->name, __("General", 'xubio-woocommerce')),
            'cient'  => array('configclient-' . $this->name, __("Client", 'xubio-woocommerce')),
            'sendtransactionmail'  => array('configtransactionmail-' . $this->name, __("Send transaction by email", 'xubio-woocommerce')),
            'receipt'  => array('configreceipt-' . $this->name, __("Receipt", 'xubio-woocommerce')),
            'proofofpurchase'  => array('configproofofpurchase-' . $this->name, __("Proof of purchase", 'xubio-woocommerce')),
            'checkin' => array('configcheckin-' . $this->name, __("Check in", 'xubio-woocommerce')),
            'purchaseorder' => array('configpurchaseorder-' . $this->name, __("Purchase order", 'xubio-woocommerce')),
            'budget' => array('configbudget-' . $this->name, __("Budget", 'xubio-woocommerce')),
            'provider' => array('configprovider-' . $this->name, __("Provider", 'xubio-woocommerce')),
            'pointofsale' => array('configpointofsale-' . $this->name, __("Point of sale", 'xubio-woocommerce')),
            'cae' => array('configcae-' . $this->name, __("CAE", 'xubio-woocommerce')),
        );
        $html =  '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ){
            $class = ($tab == $current) ? 'nav-tab-active' : '';
            $html .=  '<a class="nav-tab ' . $class . '" href="?page='.$name[0].'&tab=' . $tab . '">' . $name[1] . '</a>';
        }
        $html .= '</h2>';
        echo $html;
    }
}