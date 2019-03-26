<?php
function getQueries() {
    $result = array();

    $query_root = realpath(__DIR__.'../../query');

    $files = glob($query_root.'\**\*.php', GLOB_BRACE);

    foreach ($files as $filepath) {
        $route = str_replace($query_root.'\\', '', $filepath);
        $route = str_replace('.php', '', $route);
        require_once $filepath;

        $class      = 'Query' . preg_replace( '/[^a-zA-Z0-9]/', '', $route );
        $controller = new $class();

        $result = array_merge( $result, $controller->getQuery() );

    }

    return $result;
}