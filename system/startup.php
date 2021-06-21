<?php
define('VFA_DIR_PLUGIN', realpath(__DIR__.'/..').'/');

require_once(VFA_DIR_PLUGIN . 'system/vendor/autoload.php');
require_once(VFA_DIR_PLUGIN . 'system/engine/action.php');
require_once(VFA_DIR_PLUGIN . 'system/engine/resolver.php');
require_once(VFA_DIR_PLUGIN . 'system/engine/loader.php');
require_once(VFA_DIR_PLUGIN . 'system/engine/model.php');
require_once(VFA_DIR_PLUGIN . 'system/engine/registry.php');
require_once(VFA_DIR_PLUGIN . 'system/engine/proxy.php');
require_once(VFA_DIR_PLUGIN . 'system/library/currency.php');
require_once(VFA_DIR_PLUGIN . 'system/library/template/template.php');
require_once(VFA_DIR_PLUGIN . 'system/helpers/MySafeException.php');

function VFA_Start(WP_REST_Request $request = null) {
    $registry = new VFA_Registry();

    $loader = new VFA_Loader($registry);
    $registry->set('load', $loader);
    $registry->set('currency', new VFA_Currency());

    $registry->set('request', $request);

	return $registry;
}