<?php

class VFA_ModelStoreWishlist extends VFA_Model {
	public function getWishlist() {
		$result = array();

		$wishList = $_COOKIE['wishList'];

		if ( ! empty( $wishList ) ) {
			$result = json_decode( $wishList );
		}

		return $result;
	}

	public function addWishlist( $product_id ) {

		$wishList = $_COOKIE['wishList'];

		if ( ! empty( $wishList ) ) {
			$wishList = json_decode( $wishList, true );
		} else {
			$wishList = array();
		}
		if ( ! in_array( $product_id, $wishList ) ) {
			$wishList[] = $product_id;
			setcookie( 'wishList', json_encode( $wishList ), 0 , "/" );
			$_COOKIE['wishList'] = json_encode( $wishList );
		}
	}

	public function deleteWishlist( $product_id ) {
		$wishList = $_COOKIE['wishList'];

		$result = array();

		if ( ! empty( $wishList ) ) {
			$result = json_decode( $wishList );
		}

		$key = array_search( $product_id, $result );

		if ( $key !== false ) {
			unset( $result[ $key ] );
		}

		setcookie( 'wishList', json_encode( $result ), 0 , "/" );
		$_COOKIE['wishList'] = json_encode( $result );
	}
}
