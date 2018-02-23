<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 22/02/18
 * Time: 05:32 PM
 */

class Xubio_Api_Send_Transaction_Mail
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