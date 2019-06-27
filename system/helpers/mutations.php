<?php
function getMutations($registry) {
    $result = array();

    $files = glob(VF_DIR_PLUGIN.'type/**/*.php', GLOB_BRACE);

    foreach ($files as $filepath) {
        $route = str_replace(VF_DIR_PLUGIN.'type/', '', $filepath);
        $route = str_replace('.php', '', $route);
        $output = $registry->get('load')->type($route.'/getMutations');

        if($output) {
            $result = array_merge( $result, $output );
        }
    }

    return $result;
}