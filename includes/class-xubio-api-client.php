<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 22/02/18
 * Time: 02:49 PM
 */

class Xubio_Api_Client
{
    public function configInit()
    {
        xwsmp_xubio_woocommerce()->tabsMenu->page();
    }

    public function content()
    {
        ?>
        <div class="wrap about-wrap">
            <h1><?php _e( 'Manage customers','xubio-woocommerce' ); ?></h1>
            <div class="row">
                <form id="xublio-client" class="col s12">
                    <div class="row">
                        <div class="input-field col s3">
                            <select name="xubio-clients" class="browser-default">
                                <option value="" disabled selected><?php _e('Choose your option','xubio-woocommerce'); ?></option>
                                <option value="getclients"><?php _e('Client list','xubio-woocommerce'); ?></option>
                                <option value="createclient"><?php  _e('Create a new client','xubio-woocommerce'); ?></option>
                                <option value="getclient"><?php _e('Consult a certain client','xubio-woocommerce'); ?></option>
                                <option value="updateclient"><?php _e('Update a certain client','xubio-woocommerce'); ?></option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="overlay-xubio-woo">
                <div class="overlay-content-xubio-woo">
                    <a class="close" href="#">&times;</a>
                    <img src="<?php echo xwsmp_xubio_woocommerce()->plugin_url . 'assets/img/loading.gif';?>" alt="">
                    <div class="message"><strong></strong></div>
                </div>
            </div>
        </div>
        <?php
    }
}