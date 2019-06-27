<?php
define('VF_DIR_PLUGIN', realpath('./').'/');

require_once(VF_DIR_PLUGIN . 'system/vendor/autoload.php');
require_once(VF_DIR_PLUGIN . 'system/engine/action.php');
require_once(VF_DIR_PLUGIN . 'system/engine/actionType.php');
require_once(VF_DIR_PLUGIN . 'system/engine/resolver.php');
require_once(VF_DIR_PLUGIN . 'system/engine/type.php');
require_once(VF_DIR_PLUGIN . 'system/engine/loader.php');
require_once(VF_DIR_PLUGIN . 'system/engine/model.php');
require_once(VF_DIR_PLUGIN . 'system/engine/registry.php');
require_once(VF_DIR_PLUGIN . 'system/engine/proxy.php');
function start() {
    $registry = new Registry();

    $loader = new Loader($registry);
    $registry->set('load', $loader);

    $registry->get('load')->resolver('startup/session');
    $registry->get('load')->resolver('startup/wordpress');
    $registry->get('load')->resolver('startup/startup');
}