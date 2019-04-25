<?php

class ResolverCommonAccount extends Resolver {
	public function login( $args ) {
		$creds = array();

		$creds['user_login']    = $args["email"];
		$creds['user_password'] = $args["password"];
		$creds['remember']      = true;
		$user                   = wp_signon( $creds );

		if ( ! is_wp_error( $user ) ) {
			global $current_user;

			$current_user = $user;

			return $this->get($current_user->ID);
		} else {
			$error = reset( $user->errors );
			throw new Exception( $error[0] );
		}
	}

	public function logout( $args ) {
		wp_logout();

		return array(
			'status' => is_user_logged_in()
		);
	}

	public function register( $args ) {
		$customer = $args['customer'];

		$userdata = array(
			'user_pass'  => $customer['password'],
			'user_login' => $customer['email'],
			'user_email' => $customer['email'],
			'first_name' => $customer['firstName'],
			'last_name'  => $customer['lastName']
		);

		$user_id = wp_insert_user( $userdata );
		if ( ! is_wp_error( $user_id ) ) {
			return $this->get($user_id);
		} else {
			$error = reset( $user_id->errors );
			throw new Exception( $error[0] );
		}
    }
    
    public function edit($args) {
        global $current_user;

        return $this->get($current_user->ID);
    }

    public function editPassword($args) {
        global $current_user;

        return $this->get($current_user->ID);
    }

    public function get($user_id) {
        $user = get_user_by( 'ID', $user_id );

        return array(
            'id'        => $user->ID,
            'email'     => $user->user_email,
            'firstName' => get_user_meta( $user->ID, 'first_name', true ),
            'lastName'  => get_user_meta( $user->ID, 'last_name', true ),
        );
    }

	public function isLogged( $args ) {
		$customer = array();

		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();

			$customer = array(
				'id'        => $user->ID,
				'email'     => $user->user_email,
				'firstName' => $user->user_firstname,
				'lastName'  => $user->user_lasttname
			);
		}

		return array(
			'status'   => is_user_logged_in(),
			'customer' => $customer
		);
	}
}