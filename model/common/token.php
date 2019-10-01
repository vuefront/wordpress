<?php

use \Firebase\JWT\JWT;

class VFA_ModelCommonToken extends VFA_Model {
	public function getToken( $args ) {

		$secret_key = 'bananas';
		$username   = $args['email'];
		$password   = $args['password'];

		$user = wp_authenticate( $username, $password );

		if ( is_wp_error( $user ) ) {
			$error_code = $user->get_error_code();
			throw new Exception( $user->get_error_message( $error_code ) );
		}


		$issuedAt = time();

		$expire = $issuedAt + ( DAY_IN_SECONDS * 7 );

		$token = array(
			'iss'  => get_bloginfo( 'url' ),
			'iat'  => $issuedAt,
			'nbf'  => $issuedAt,
			'exp'  => $expire,
			'data' => array(
				'user' => array(
					'id' => $user->data->ID,
				),
			),
		);

		$token = JWT::encode( $token, $secret_key );

		$data = array(
			'token'             => $token,
			'expire'            => $expire,
			'user_id'           => $user->data->ID,
			'user_email'        => $user->data->user_email,
			'user_nicename'     => $user->data->user_nicename,
			'user_display_name' => $user->data->display_name,
		);

		return $data;
	}

	public function validateToken( $output ) {
		if (!function_exists('getallheaders')) {
		    function getallheaders() {
		    $headers = [];
		    foreach ($_SERVER as $name => $value) {
		        if (substr($name, 0, 5) == 'HTTP_') {
		            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
		        }
		    }
		    return $headers;
		    }
		}
		
		$headers = getallheaders();


		$auth    = isset( $headers['Authorization'] ) ? $headers['Authorization'] : false;
		if ( ! $auth ) {
			return false;
		}

		list( $token ) = sscanf( $auth, 'Bearer %s' );

		if ( ! $token ) {
			return false;
		}

		$secret_key = 'bananas';

		try {
			$token = JWT::decode( $token, $secret_key, array( 'HS256' ) );

			if ( $token->iss != get_bloginfo( 'url' ) ) {
				return false;
			}
			if ( ! isset( $token->data->user->id ) ) {
				return false;
			}
			if ( ! $output ) {
				return $token;
			}

			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
}