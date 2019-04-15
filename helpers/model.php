<?php

function getModel( $path ) {
    $file  = realpath( __DIR__.'/../model/' . $path . '.php' );

    $class = 'Model' . preg_replace( '/[^a-zA-Z0-9]/', '', $path );

    include_once( $file );

    $model = new $class();

    return $model;
}