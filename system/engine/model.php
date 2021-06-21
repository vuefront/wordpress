<?php 
/**
 * @property VFA_Loader $load
 * @property WP_REST_Request $request
 */
abstract class VFA_Model {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
}