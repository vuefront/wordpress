<?php
define('VF_DIR_PLUGIN', realpath(__DIR__.'/..').'/');

require_once(VF_DIR_PLUGIN . 'system/vendor/autoload.php');
require_once(VF_DIR_PLUGIN . 'system/engine/action.php');
require_once(VF_DIR_PLUGIN . 'system/engine/actionType.php');
require_once(VF_DIR_PLUGIN . 'system/engine/resolver.php');
require_once(VF_DIR_PLUGIN . 'system/engine/type.php');
require_once(VF_DIR_PLUGIN . 'system/engine/loader.php');
require_once(VF_DIR_PLUGIN . 'system/engine/model.php');
require_once(VF_DIR_PLUGIN . 'system/engine/registry.php');
require_once(VF_DIR_PLUGIN . 'system/engine/proxy.php');

function start(WP_REST_Request $request = null) {
    $registry = new Registry();

    $loader = new Loader($registry);
    $registry->set('load', $loader);

    $registry->set('request', $request);

	return $registry;
}