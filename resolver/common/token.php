<?php

class ResolverCommonToken extends Resolver {
	public function get() {
		$this->load->model('common/token');
		$token_info = $this->model_common_token->getToken();
		return array(
			'token' =>$token_info['token'],
			'expire' => $token_info['expire']
		);
	}
}