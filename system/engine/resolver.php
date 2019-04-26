<?php
/**
 * @property Loader $load
 * @property ModelBlogCategory $model_blog_category
 * @property ModelBlogPost $model_blog_post
 * @property ModelCommonPage $model_common_page
 * @property ModelStartupStartup $model_startup_startup
 * @property ModelStoreCategory $model_store_category
 * @property ModelStoreCompare $model_store_compare
 * @property ModelStoreOption $model_store_option
 * @property ModelStoreProduct $model_store_product
 * @property ModelStoreWishlist $model_store_wishlist
 */
abstract class Resolver {
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