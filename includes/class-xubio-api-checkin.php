<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 22/02/18
 * Time: 06:05 PM
 */

class Xubio_Api_Checkin
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