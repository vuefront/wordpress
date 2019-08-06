<?php
/**
 * @property VF_Loader $load
 * @property VF_ModelBlogCategory $model_blog_category
 * @property VF_ModelBlogPost $model_blog_post
 * @property VF_ModelCommonPage $model_common_page
 * @property VF_ModelStartupStartup $model_startup_startup
 * @property VF_ModelStoreCategory $model_store_category
 * @property VF_ModelStoreCompare $model_store_compare
 * @property VF_ModelStoreOption $model_store_option
 * @property VF_ModelStoreProduct $model_store_product
 * @property VF_ModelStoreWishlist $model_store_wishlist
 * @property VF_ModelCommonToken $model_common_token
 * @property WP_REST_Request $request
 */
abstract class VF_Resolver {
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