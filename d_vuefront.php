<?php
/*
Plugin Name: Vue Front
Description: Vue front.
Version: 1.0
Author: Dreamvention
Author URI: https://dreamvention.com
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'helpers/queries.php';
require_once 'helpers/mutations.php';

use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Schema\Schema;
use Youshido\GraphQL\Type\Object\ObjectType;

function graphql( WP_REST_Request $request ) {

    $processor = new Processor(
        new Schema(
            array(
                'query' => new ObjectType(
                    array(
                        'name'   => 'RootQueryType',
                        'fields' => getQueries()
                    )
                ),
                'mutation' => new ObjectType(
                    array(
                        'name' => 'RootMutationType',
                        'fields' => getMutations()
                    )
                )
            )
        )
    );
    $params    = $request->get_json_params();

    if ( ! empty( $params['variables'] ) ) {
        $processor->processPayload( $params['query'], $params['variables'] );

    } else {
        $processor->processPayload( $params['query'] );
    }

    return $processor->getResponseData();
}

function my_register_vuefront_api() {
    register_rest_route( 'vuefront/v1', '/graphql', array(
        'methods'  => 'POST',
        'callback' => 'graphql',
    ) );
}

add_action( 'rest_api_init', 'my_register_vuefront_api' );


