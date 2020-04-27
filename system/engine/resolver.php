<?php
/**
 * @property VFA_Loader $load
 * @property VFA_ModelBlogCategory $model_blog_category
 * @property VFA_ModelBlogPost $model_blog_post
 * @property VFA_ModelCommonPage $model_common_page
 * @property VFA_ModelStartupStartup $model_startup_startup
 * @property VFA_ModelStoreCategory $model_store_category
 * @property VFA_ModelStoreCompare $model_store_compare
 * @property VFA_ModelStoreOption $model_store_option
 * @property VFA_ModelStoreProduct $model_store_product
 * @property VFA_ModelStoreWishlist $model_store_wishlist
 * @property VFA_ModelCommonToken $model_common_token
 * @property WP_REST_Request $request
 */
abstract class VFA_Resolver {
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