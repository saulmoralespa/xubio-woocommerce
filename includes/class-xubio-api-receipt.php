<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 22/02/18
 * Time: 05:44 PM
 */

class Xubio_Api_Receipt
{
    public function configInit()
    {
        xwsmp_xubio_woocommerce()->tabsMenu->page();
    }

    public function content()
    {
        echo 'content';
    }
}