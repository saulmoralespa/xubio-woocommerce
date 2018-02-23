<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 22/02/18
 * Time: 12:17 PM
 */

class Xubio_Admin_Configuration
{
    public function configInit()
    {
        xwsmp_xubio_woocommerce()->tabsMenu->page();
    }

    public function content()
    {
        ?>
        <div class="wrap about-wrap">
            <h1><?php _e( 'client-id and secret-id of the xubio Client App','xubio-woocommerce' ); ?></h1>
            <form id="xubio-woo-credentials">
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th><?php echo __('Client id','xubio-woocommerce');?></th>
                        <td>
                            <input type="text" name="client_id" value="" required>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __('Client secret','xubio-woocommerce');?></th>
                        <td>
                            <input type="text" name="client_secret" value="" required>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php submit_button(); ?>
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