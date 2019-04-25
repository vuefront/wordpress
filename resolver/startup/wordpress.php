<?php

class ResolverStartupWordpress extends Resolver {
	public function index() {
		require_once realpath( DIR_PLUGIN . '../../../wp-load.php' );
//        require_once realpath(DIR_PLUGIN.'system/helpers/load.php');
	}
}