<?php

use GraphQL\GraphQL;
use GraphQL\Utils\BuildSchema;

class ResolverStartupStartup extends Resolver {
	public function index() {

//		if ( is_plugin_active( 'd_vuefront/plugin.php' ) ) {
		if ( $this->request->get_param( 'cors' ) ) {
			if ( ! empty( $_SERVER['HTTP_ORIGIN'] ) ) {
				header( 'Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN'] );
			} else {
				header( 'Access-Control-Allow-Origin: *' );
			}
			header( 'Access-Control-Allow-Methods: POST, OPTIONS' );
			header( 'Access-Control-Allow-Credentials: true' );
			header( 'Access-Control-Allow-Headers: DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,Token,token,Cookie,cookie,content-type' );
		}

		$this->load->model( 'startup/startup' );

		try {
			$resolvers = $this->model_startup_startup->getResolvers();
			$schema    = BuildSchema::build( file_get_contents( VF_DIR_PLUGIN . 'schema.graphql' ) );
			$rawInput  = file_get_contents( 'php://input' );
			$input     = json_decode( $rawInput, true );
			$query     = $input['query'];

			$variableValues = isset( $input['variables'] ) ? $input['variables'] : null;
			$result         = GraphQL::executeQuery( $schema, $query, $resolvers, null, $variableValues );
		} catch ( \Exception $e ) {
			$result = [
				'error' => [
					'message' => $e->getMessage()
				]
			];
		}


		foreach ( $_COOKIE as $key => $value ) {
			if ( strpos( $key, 'woocommerce' ) >= 0 ) {
				setcookie( $key, $value, 0, "/" );
			}
		}

		return $result;
	}

	public function determine_current_user( $user ) {
		$this->load->model( 'common/token' );

		$validate_uri = strpos( $_SERVER['REQUEST_URI'], 'token/validate' );
		if ( $validate_uri > 0 ) {
			return $user;
		}

		$token = $this->model_common_token->validateToken( false );

		if ( ! $token ) {
			return $user;
		}

		return $token->data->user->id;
	}
//	}
}
