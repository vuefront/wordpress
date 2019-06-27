<?php
/**
 * Plugin Name: Vuefront
 * Plugin URI: https://github.com/vuefront/wordpress
 * Description: VueFront CMS Connect App for Wordpress.
 * Version: 1.0
 * Author: Dreamvention
 * Author URI: http://dreamvention.com
 */

/**
* Создаем страницу настроек плагина
*/

add_action('admin_menu', 'add_plugin_page');
function add_plugin_page()
{
    $codename = 'd_vuefront';
    $page_hook_suffix= add_options_page( __('Settings', $codename).' Vuefront', 'Vuefront', 'manage_options', 'd_vuefront', 'vuefront_options_page_output');
    add_action('admin_print_scripts-' . $page_hook_suffix, 'my_plugin_admin_scripts');
}

function my_plugin_admin_scripts() {
    wp_enqueue_style( 'vuefront-style', plugins_url('d_vuefront/view/stylesheet/admin.css') );
    wp_enqueue_style( 'bootstrap-style', plugins_url('d_vuefront/view/stylesheet/bootstrap.min.css') );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'clipboard' );
    wp_enqueue_script( 'bootstrap-script', plugins_url('d_vuefront/view/javascript/bootstrap.min.js') );
}

function vuefront_options_page_output()
{
    $codename = 'd_vuefront';

    $data = array();
    $data['text_title'] = __('CMS Connect URL', $codename);
    $data['text_description'] =  __('This is your CMS Connect URL link that shares your Blog data via GraphQL. When installing VueFront via the command line, you will be prompted to enter this URL. Simply copy and paste it into the command line.
    <br><br>
    Read more about the <a href="https://vuefront.com/cms/wordpress.html" target="_blank">CMS Connect for Wordpress</a>', $codename);
    $data['text_woocommerce_plugin'] =  __('WooCommerce', $codename);
    $data['text_woocommerce_enabled'] =  __('WooCommerce active', $codename);
    $data['text_woocommerce_description'] =  sprintf(__('VueFront relies on the free <a href="%s" target="_blank">WooCommerce</a> plugin to implement store. The store feature is optional and VueFront will work fine without it. You can install it via Wordpress.', $codename), 'https://ru.wordpress.org/plugins/woocommerce/');
    $data['text_woocommerce_disabled'] =  __('WooCommerce missing. Click to download', $codename);
    $data['text_copy'] =  __('copy', $codename);
    $data['text_copied'] =  __('copied!', $codename);
    $data['catalog'] = plugins_url('d_vuefront/index.php');
    $data['woocommerce'] = is_plugin_active( 'woocommerce/woocommerce.php' );
    $data['logo'] = plugins_url('d_vuefront/view/image/logo.png');
    extract($data);
    require_once 'view/template/setting.tpl';
}

function my_plugin_action_links($links)
{
    $links = array_merge(array(
        '<a href="' . esc_url(admin_url('options-general.php?page=d_vuefront')) . '">' .  __('Settings') . '</a>'
    ), $links);
    return $links;
}
add_action('plugin_action_links_' . plugin_basename(__FILE__), 'my_plugin_action_links');

add_action( 'plugins_loaded', 'true_load_plugin_textdomain' );
 
function true_load_plugin_textdomain() {
	load_plugin_textdomain( 'd_vuefront', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}