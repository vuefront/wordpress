<?php

class ResolverCommonAccount extends Resolver {
	public function login( $args ) {

		try {
			$this->load->model( 'common/token' );
			$token_info = $this->model_common_token->getToken( $args );

			return array( 'token' => $token_info['token'], 'customer' => $this->get( $token_info['user_id'] ) );

		} catch ( \Exception $e ) {
			throw new Exception( $e->getMessage() );
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
			return $this->get( $user_id );
		} else {
			$error = reset( $user_id->errors );
			throw new Exception( $error[0] );
		}
	}

	public function edit( $args ) {
		global $current_user;

		$customer_data = $args['customer'];

		$current_user->first_name = $customer_data['firstName'];
		$current_user->last_name  = $customer_data['lastName'];
		$current_user->user_email = $customer_data['email'];

		wp_update_user( $current_user );

		return $this->get( $current_user->ID );
	}

	public function editPassword( $args ) {
		global $current_user;

		return $this->get( $current_user->ID );
	}

	public function get( $user_id ) {
		$user = get_user_by( 'ID', $user_id );

		return array(
			'id'        => $user->ID,
			'email'     => $user->user_email,
			'firstName' => $user->first_name,
			'lastName'  => $user->last_name
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
				'lastName'  => $user->user_lastname
			);
		}

		return array(
			'status'   => is_user_logged_in(),
			'customer' => $customer
		);
	}

	public function address( $args ) {
		$address = array();

		global $current_user;

		switch ( $args['id'] ) {
			case 'billing':
				$address = array(
					'id'        => 'billing',
					'firstName' => get_user_meta( $current_user->ID, 'billing_first_name', true ),
					'lastName'  => get_user_meta( $current_user->ID, 'billing_last_name', true ),
					'company'   => get_user_meta( $current_user->ID, 'billing_company', true ),
					'address1'  => get_user_meta( $current_user->ID, 'billing_address_1', true ),
					'address2'  => get_user_meta( $current_user->ID, 'billing_address_2', true ),
					'zoneId'    => '',
					'zone'      => array(
						'id'   => '',
						'name' => ''
					),
					'country'   => $this->load->resolver( 'common/country/get', array(
						'id' => get_user_meta( $current_user->ID, 'billing_country', true )
					) ),
					'countryId' => get_user_meta( $current_user->ID, 'billing_country', true ),
					'city'      => get_user_meta( $current_user->ID, 'billing_city', true ),
					'zipcode'   => get_user_meta( $current_user->ID, 'billing_postcode', true )
				);
				break;
			case 'shipping':
				$address = array(
					'id'        => 'shipping',
					'firstName' => get_user_meta( $current_user->ID, 'shipping_first_name', true ),
					'lastName'  => get_user_meta( $current_user->ID, 'shipping_last_name', true ),
					'company'   => get_user_meta( $current_user->ID, 'shipping_company', true ),
					'address1'  => get_user_meta( $current_user->ID, 'shipping_address_1', true ),
					'address2'  => get_user_meta( $current_user->ID, 'shipping_address_2', true ),
					'zoneId'    => '',
					'zone'      => array(
						'id'   => '',
						'name' => ''
					),
					'country'   => $this->load->resolver( 'common/country/get', array(
						'id' => get_user_meta( $current_user->ID, 'shipping_country', true )
					) ),
					'countryId' => get_user_meta( $current_user->ID, 'shipping_country', true ),
					'city'      => get_user_meta( $current_user->ID, 'shipping_city', true ),
					'zipcode'   => get_user_meta( $current_user->ID, 'shipping_postcode', true )
				);
				break;
		}

		return $address;
	}

	public function addressList( $args ) {

		$ids = array( 'billing', 'shipping' );

		$address = array();

		foreach ( $ids as $value ) {
			$address[] = $this->address( array( 'id' => $value ) );
		}

		return $address;
	}

	public function editAddress( $args ) {
		global $current_user;

		$prefix = $args['id'];

		$data = array(
			'firstName' => '_first_name',
			'lastName'  => '_last_name',
			'company'   => '_company',
			'address1'  => '_address_1',
			'address2'  => '_address_2',
			'countryId' => '_country',
			'city'      => '_city',
			'zipcode'   => '_postcode'
		);

		foreach ( $data as $key => $value ) {
			update_user_meta( $current_user->ID, $prefix . $value, $args['address'][ $key ] );
		}

		return $this->address( $args );
	}

	public function addAddress( $args ) {
		throw new Exception( 'Adding an address is not possible in Wordpress' );
	}

	public function removeAddress( $args ) {
		throw new Exception( 'Removing an address is not possible in Wordpress' );
	}
}