<?php

class VFA_ModelStoreCompare extends VFA_Model {
	public function getCompare() {
		$result = array();

		$compare = $_COOKIE['compare'];

		if ( ! empty( $compare ) ) {
			$result = json_decode( $compare );
		}

		return $result;
	}

	public function addCompare( $product_id ) {
		$compare = $_COOKIE['compare'];

		if ( ! empty( $compare ) ) {
			$compare = json_decode( $compare, true );
		} else {
			$compare = array();
		}
		if ( ! in_array( $product_id, $compare ) ) {
			if ( count( $compare ) >= 4 ) {
				array_shift( $compare );
			}
			$compare[] = $product_id;
			setcookie( 'compare', json_encode( $compare ), 0, "/" );
			$_COOKIE['compare'] = json_encode( $compare );
		}
	}

	public function deleteCompare( $product_id ) {
		$compare = $_COOKIE['compare'];

		$result = array();

		if ( ! empty( $compare ) ) {
			$result = json_decode( $compare );
		}

		$key = array_search( $product_id, $result );

		if ( $key !== false ) {
			unset( $result[ $key ] );
		}

		setcookie( 'compare', json_encode( $result ), 0, "/" );
		$_COOKIE['compare'] = json_encode( $result );
	}
}
