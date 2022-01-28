<?php
/**
 * Plugin Name: VueFront
 * Plugin URI: https://github.com/vuefront/wordpress
 * Description: VueFront CMS Connect App for Wordpress.
 * Version: 2.1.1
 * Author: VueFront
 * Author URI: http://vuefront.com
 */

require_once 'system/startup.php';

add_filter('woocommerce_is_rest_api_request', 'VFA_simulate_as_not_rest');
function VFA_simulate_as_not_rest($is_rest_api_request)
{
    if (empty($_SERVER['REQUEST_URI'])) {
        return $is_rest_api_request;
    }

    if (false === strpos($_SERVER['REQUEST_URI'], 'vuefront')) {
        return $is_rest_api_request;
    }

    return false;
}

add_action('admin_menu', 'VFA_add_plugin_page');
add_action('admin_enqueue_scripts', 'VFA_vuefront_admin_styles');
add_action('wp_ajax_vf_settings', 'VFA_vuefront_admin_action_settings');
add_action('wp_ajax_vf_settings_edit', 'VFA_vuefront_admin_action_settings_edit');
add_action('wp_ajax_vf_apps', 'VFA_vuefront_admin_action_apps');
add_action('wp_ajax_vf_apps_create', 'VFA_vuefront_admin_action_apps_create');
add_action('wp_ajax_vf_apps_edit', 'VFA_vuefront_admin_action_apps_edit');
add_action('wp_ajax_vf_apps_remove', 'VFA_vuefront_admin_action_apps_remove');
add_action('wp_ajax_vf_register', 'VFA_vuefront_admin_action_register');
add_action('wp_ajax_vf_turn_on', 'VFA_vuefront_admin_action_turn_on');
add_action('wp_ajax_vf_update', 'VFA_vuefront_admin_action_update');
add_action('wp_ajax_vf_turn_off', 'VFA_vuefront_admin_action_turn_off');
add_action('wp_ajax_vf_information', 'VFA_vuefront_admin_action_vf_information');
register_activation_hook (__FILE__, 'VFA_install');
add_action( 'plugins_loaded', 'VFA_update_db_check' );

function VFA_vuefront_admin_styles()
{
    wp_register_style('vuefront_admin_menu_styles', plugin_dir_url(__FILE__) . 'view/stylesheet/menu.css');
    wp_enqueue_style('vuefront_admin_menu_styles');
}

function VFA_add_plugin_page()
{
    $codename         = 'vuefront';
    $page_hook_suffix = add_menu_page(__('Settings', $codename) . ' Vuefront', 'Vuefront', 'manage_options', 'vuefront', 'VFA_vuefront_admin_general', plugin_dir_url(__FILE__).'view/image/icon_admin.svg', 55.8);

    // $page_hook_suffix = add_options_page( __( 'Settings', $codename ) . ' Vuefront', 'Vuefront', 'manage_options', 'vuefront', 'VFA_vuefront_admin_general' );

    add_action('admin_print_scripts-' . $page_hook_suffix, 'VFA_my_plugin_admin_scripts');
}

function VFA_vuefront_rmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object) && ! is_link($dir . "/" . $object)) {
                    VFA_vuefront_rmdir($dir . "/" . $object);
                } else {
                    unlink($dir . "/" . $object);
                }
            }
        }
        rmdir($dir);
    }
}

function VFA_vuefront_admin_action_settings() {
    $vfSetting = get_option('vuefront-settings') ? get_option('vuefront-settings') : array();
    echo json_encode(
        $vfSetting
    , JSON_FORCE_OBJECT);

    wp_die();
}

function VFA_vuefront_admin_action_settings_edit() {
        $vfSetting = json_decode(stripslashes(html_entity_decode($_POST['setting'], ENT_QUOTES, 'UTF-8')), true);
        update_option('vuefront-settings', $vfSetting);

        echo json_encode(
            array('success' => 'success')
        );
    
        wp_die();
}

function VFA_vuefront_admin_action_vf_information()
{
    $plugin_data = get_plugin_data(__FILE__);
    $woocommerce_data = get_plugin_data(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php');
    $brand_data = get_plugin_data(WP_PLUGIN_DIR . '/perfect-woocommerce-brands/perfect-woocommerce-brands.php');
    $plugin_version = $plugin_data['Version'];
    $extensions = array();
    $extensions[] = array(
        'name' => 'WooCommerce',
        'version' => $woocommerce_data['Version'],
        'status' => is_plugin_active('woocommerce/woocommerce.php')
    );

    $extensions[] = array(
        'name' => "Perfect Brands for WooCommerce",
        'version' => $brand_data['Version'],
        'status' => is_plugin_active('perfect-woocommerce-brands/perfect-woocommerce-brands.php')
    );

    $status = file_exists(__DIR__ . '/.htaccess.txt');
    
    echo json_encode(
        array(
            'apache' => strpos($_SERVER["SERVER_SOFTWARE"], "Apache") !== false,
            'backup' => 'wp-content/plugins/vuefront/.htaccess.txt',
            'htaccess' => file_exists(ABSPATH . '.htaccess'),
            'status' => $status,
            'server' => $_SERVER['SERVER_SOFTWARE'],
            'phpversion' => phpversion(),
            'plugin_version' => $plugin_version,
            'extensions' =>  $extensions,
            'cmsConnect' => get_rest_url(null, '/vuefront/v1/graphql')
        )
    );
    wp_die();
}

function VFA_vuefront_admin_action_apps_create()
{
    $setting = get_option('vuefront-apps');

    $d = new DateTime();
        
    $setting[] = array(
        'codename' => $_POST['codename'],
        'jwt' => $_POST['jwt'],
        'dateAdded' => $d->format('Y-m-d\TH:i:s.u')
    );


    update_option('vuefront-apps', $setting);

    echo json_encode(
        array('success' => 'success')
    );

    wp_die();
}

function VFA_install () {
   global $wpdb;

   $table_name = $wpdb->prefix . "vuefront_url"; 

   $charset_collate = $wpdb->get_charset_collate();

   $sql = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
            `id_url` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `id` varchar( 255 ) NOT NULL,
            `type` varchar(64) NOT NULL,
            `url` varchar(255) NOT NULL,
            PRIMARY KEY (`id_url`)
        ) '.$charset_collate;

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function VFA_update_db_check() {
    global $vfa_db_version;
    if ( get_site_option( 'vfa_db_version' ) != $vfa_db_version ) {
        VFA_install();
    }
}

function VFA_vuefront_admin_action_apps_edit() {
    $setting = get_option('vuefront-apps');

    $app = json_decode(stripslashes(html_entity_decode($_POST['app'], ENT_QUOTES, 'UTF-8')), true);

    foreach ($app as $key => $value) {
        $setting[$_POST['key']][$key] = $value;
    }

    update_option('vuefront-apps', $setting);

    echo json_encode(
        array('success' => 'success')
    );

    wp_die();
}

function VFA_vuefront_admin_action_apps()
{
    echo json_encode(
        get_option('vuefront-apps')
    );

    wp_die();
}

function VFA_vuefront_admin_action_apps_remove()
{
    $setting = get_option('vuefront-apps');
    unset($setting[$_POST['key']]);
    update_option('vuefront-apps', $setting);

    echo json_encode(
        array('success' => 'success')
    );

    wp_die();
}


function VFA_vuefront_admin_action_turn_off()
{
    if (strpos($_SERVER["SERVER_SOFTWARE"], "Apache") !== false) {
        if (file_exists(__DIR__ . '/.htaccess.txt')) {
            if (!is_writable(ABSPATH . '.htaccess') || !is_writable(__DIR__.'/.htaccess.txt')) {
                $error = new WP_Error('500', 'not_writable_htaccess');

                wp_send_json_error($error, 500);
                return;
            }
            $content = file_get_contents(__DIR__.'/.htaccess.txt');
            file_put_contents(ABSPATH.'.htaccess', $content);
            unlink(__DIR__.'/.htaccess.txt');
        }
    }
    VFA_vuefront_admin_action_vf_information();
}

function VFA_vuefront_admin_action_turn_on()
{
    try {
        if (strpos($_SERVER["SERVER_SOFTWARE"], "Apache") !== false) {
            $catalog = get_site_url();
            $catalog_url_info = parse_url($catalog);

            $catalog_path = $catalog_url_info['path'];

            $catalog_path = $catalog_path . '/';
            $document_path = $catalog_path;
            if (!empty($_SERVER['DOCUMENT_ROOT'])) {
                $document_path = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', ABSPATH) . '/';
            }
            if (!file_exists(ABSPATH . '.htaccess')) {
                file_put_contents(ABSPATH.'.htaccess', "# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase ".$catalog_path."
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php [L]
</IfModule>

# END WordPress
");
            }

            if (!is_writable(ABSPATH . '.htaccess')) {
                $error = new WP_Error('500', 'not_writable_htaccess');

                wp_send_json_error($error, 500);
                return;
            }

            if (file_exists(ABSPATH . '.htaccess')) {
                $inserting = "# VueFront scripts, styles and images
RewriteCond %{REQUEST_URI} .*(_nuxt)
RewriteCond %{REQUEST_URI} !.*/vuefront/_nuxt
RewriteRule ^([^?]*) vuefront/$1

# VueFront sw.js
RewriteCond %{REQUEST_URI} .*(sw.js)
RewriteCond %{REQUEST_URI} !.*/vuefront/sw.js
RewriteRule ^([^?]*) vuefront/$1

# VueFront favicon.ico
RewriteCond %{REQUEST_URI} .*(favicon.ico)
RewriteCond %{REQUEST_URI} !.*/vuefront/favicon.ico
RewriteRule ^([^?]*) vuefront/$1

# VueFront pages

# VueFront home page
RewriteCond %{REQUEST_URI} !.*(image|.php|admin|catalog|\/img\/.*\/|wp-json|wp-admin|wp-content|checkout|rest|static|order|themes\/|modules\/|js\/|\/vuefront\/)
RewriteCond %{QUERY_STRING} !.*(rest_route)
RewriteCond %{DOCUMENT_ROOT}".$document_path."vuefront/index.html -f
RewriteRule ^$ vuefront/index.html [L]

RewriteCond %{REQUEST_URI} !.*(image|.php|admin|catalog|\/img\/.*\/|wp-json|wp-admin|wp-content|checkout|rest|static|order|themes\/|modules\/|js\/|\/vuefront\/)
RewriteCond %{QUERY_STRING} !.*(rest_route)
RewriteCond %{DOCUMENT_ROOT}".$document_path."vuefront/index.html !-f
RewriteRule ^$ vuefront/200.html [L]

# VueFront page if exists html file
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !.*(image|.php|admin|catalog|\/img\/.*\/|wp-json|wp-admin|wp-content|checkout|rest|static|order|themes\/|modules\/|js\/|\/vuefront\/)
RewriteCond %{QUERY_STRING} !.*(rest_route)
RewriteCond %{DOCUMENT_ROOT}".$document_path."vuefront/$1.html -f
RewriteRule ^([^?]*) vuefront/$1.html [L,QSA]

# VueFront page if not exists html file
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !.*(image|.php|admin|catalog|\/img\/.*\/|wp-json|wp-admin|wp-content|checkout|rest|static|order|themes\/|modules\/|js\/|\/vuefront\/)
RewriteCond %{QUERY_STRING} !.*(rest_route)
RewriteCond %{DOCUMENT_ROOT}".$document_path."vuefront/$1.html !-f
RewriteRule ^([^?]*) vuefront/200.html [L,QSA]";

                $content = file_get_contents(ABSPATH . '.htaccess');

                file_put_contents(__DIR__.'/.htaccess.txt', $content);

                preg_match('/# VueFront pages/m', $content, $matches);

                if (count($matches) == 0) {
                    $content = preg_replace_callback('/RewriteBase\s.*$/m', function ($matches) use ($inserting) {
                        return $matches[0].PHP_EOL.$inserting.PHP_EOL;
                    }, $content);

                    file_put_contents(ABSPATH.'.htaccess', $content);
                }
            }
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }

    VFA_vuefront_admin_action_vf_information();
}

function VFA_vuefront_admin_action_update()
{
    try {
        $tmpFile = download_url($_POST['url']);
        VFA_vuefront_rmdir(ABSPATH . 'vuefront');
        $phar = new PharData($tmpFile);
        $phar->extractTo(ABSPATH . 'vuefront');
    } catch (\Exception $e) {
        echo $e->getMessage();
    }

    VFA_vuefront_admin_action_vf_information();
}

function VFA_vuefront_admin_general()
{
    require_once 'view/template/general.tpl';
}

function VFA_my_plugin_admin_scripts()
{
    $pax_dist = plugin_dir_path(__FILE__).'view/javascript/d_vuefront/';

    if (!file_exists(ABSPATH.'wp-includes/js/dist/vendor/wp-polyfill.js')) {
        wp_enqueue_script('vf-polyfill', plugin_dir_url(__FILE__).'view/javascript/polyfill.js');
    }
    $app = json_decode(file_get_contents($pax_dist . 'manifest.json'), true);
    $current_chunk = $app['files'];
    while (!empty($current_chunk)) {
        foreach ($current_chunk['js'] as $value) {
            wp_enqueue_script(basename($value), $value);
        }
        foreach ($current_chunk['css'] as $value) {
            wp_enqueue_style(basename($value), $value);
        }
        $current_chunk = $current_chunk['next'];
    }
}

function VFA_vuefront_api_proxy(WP_REST_Request $request)
{
    $url_params = $request->get_params();
    $body = $request->get_json_params();
    $headers = $request->get_headers();

    $cHeaders = array('Content-Type: application/json');

    if (!empty($headers['token'])) {
        $cHeaders[] = 'token: '.$headers['token'][0];
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.vuefront.com/graphql');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $cHeaders);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

function VFA_vuefront_register_vuefront_api()
{
    register_rest_route('vuefront/v1', '/proxy', array(
    'methods' => 'POST',
    'callback' => 'VFA_vuefront_api_proxy',
  ));
}

add_action('rest_api_init', 'VFA_vuefront_register_vuefront_api');

function VFA_my_plugin_action_links($links)
{
    $links = array_merge(array(
        '<a href="' . esc_url(admin_url('admin.php?page=vuefront')) . '">' . __('Settings') . '</a>'
    ), $links);

    return $links;
}

add_action('plugin_action_links_' . plugin_basename(__FILE__), 'VFA_my_plugin_action_links');

add_action('plugins_loaded', 'VFA_true_load_plugin_textdomain');

function VFA_true_load_plugin_textdomain()
{
    load_plugin_textdomain('vuefront', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

function VFA_RestApi(WP_REST_Request $request)
{
    $registry = VFA_Start();

    $registry->set('request', $request);

    $output = $registry->get('load')->resolver('startup/startup');

    return $output;
}

function VFA_Callback(WP_REST_Request $request)
{
    $registry = VFA_Start();

    $registry->set('request', $request);

    $output = $registry->get('load')->resolver('store/checkout/callback');

    return $output;
}

add_action('determine_current_user', function ($user) {
    $registry = VFA_Start();

    return $registry->get('load')->resolver('startup/startup/determine_current_user', $user);
}, 10);

add_action('rest_api_init', function () {
    register_rest_route('vuefront/v1', '/graphql', array(
        'methods'  => 'POST',
        'callback' => 'VFA_RestApi',
    ));

    register_rest_route('vuefront/v1', '/callback', array(
        'methods'  => 'POST',
        'callback' => 'VFA_Callback',
    ));
});

add_action('wp', function () {
    $headers = headers_list();
    $cookies = array();

    foreach ($headers as $header) {
        if (strpos($header, 'Set-Cookie: ') === 0) {
            if (preg_match('/path=(.*);/i', $header)) {
                $cookies[] = preg_replace('/path=(.*);/i', 'path=/;', $header);
            } elseif (preg_match('/path=(.*)/i', $header)) {
                $cookies[] = preg_replace('/path=(.*)/i', 'path=/', $header);
            }
        }
    }

    if (! headers_sent()) {
        for ($i = 0; $i < count($cookies); $i ++) {
            if ($i == 0) {
                header($cookies[ $i ]);
            } else {
                header($cookies[ $i ], false);
            }
        }
    }
}, 99);
add_action('woocommerce_add_to_cart', function () {
    $headers = headers_list();
    $cookies = array();

    foreach ($headers as $header) {
        if (strpos($header, 'Set-Cookie: ') === 0) {
            if (preg_match('/path=(.*);/i', $header)) {
                $cookies[] = preg_replace('/path=(.*);/i', 'path=/;', $header);
            } elseif (preg_match('/path=(.*)/i', $header)) {
                $cookies[] = preg_replace('/path=(.*)/i', 'path=/', $header);
            }
        }
    }

    if (! headers_sent()) {
        for ($i = 0; $i < count($cookies); $i ++) {
            if ($i == 0) {
                header($cookies[ $i ]);
            } else {
                header($cookies[ $i ], false);
            }
        }
    }
}, 99);

add_action('shutdown', function () {
    $headers = headers_list();
    $cookies = array();


    foreach ($headers as $header) {
        if (strpos($header, 'Set-Cookie: ') === 0) {
            if (preg_match('/path=(.*);/i', $header)) {
                $cookies[] = preg_replace('/path=(.*);/i', 'path=/;', $header);
            } elseif (preg_match('/path=(.*)/i', $header)) {
                $cookies[] = preg_replace('/path=(.*)/i', 'path=/', $header);
            }
        }
    }

    if (! headers_sent()) {
        for ($i = 0; $i < count($cookies); $i ++) {
            if ($i == 0) {
                header($cookies[ $i ]);
            } else {
                header($cookies[ $i ], false);
            }
        }
    }
}, 1);
