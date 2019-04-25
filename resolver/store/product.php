<?php

class ResolverStoreProduct extends Resolver
{
    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('store/product');
    }

    public function get($args) {
        $product = $this->model_store_product->getProduct($args['id']);

        if (!empty($product->image_id)) {
            $product_image      = wp_get_attachment_image_src($product->image_id, 'full');
            $product_lazy_image = wp_get_attachment_image_src($product->image_id, array( 10, 10 ));

            $thumb              = $product_image[0];
            $thumbLazy          = $product_lazy_image[0];
        } else {
            $thumb      = wc_placeholder_img_src('full');
            $thumbLazy = wc_placeholder_img_src(array( 10, 10 ));
        }

        if ($product->type == 'variable') {
            $variation_id = $this->model_store_product->getVariationLowPrice($product->ID);

            $variation_product = $this->model_store_product->getProduct($variation_id);

            $price = $variation_product->price . ' ' . $this->model_store_product->getCurrencySymbol();

            if ($variation_product->special > 0) {
                $special = $variation_product->special . ' ' . $this->model_store_product->getCurrencySymbol();
            } else {
                $special = '';
            }
        } else {
            $price = $product->price . ' ' . $this->model_store_product->getCurrencySymbol();

            if ($product->special > 0) {
                $special = $product->special . ' ' . $this->model_store_product->getCurrencySymbol();
            } else {
                $special = '';
            }
        }

        $product_info = array(
            'id'               => $product->ID,
            'name'             => $product->name,
            'description'      => $product->description,
            'shortDescription' => $product->short_description,
            'price'            => $price,
            'special'          => $special,
            'model'            => $product->model,
            'image'            => $thumb,
            'imageLazy'        => $thumbLazy,
            'stock'            => $product->stock_status === 'instock',
            'rating'           => (float) $product->rating,
            'images' => function($root, $args) {
                return $this->getImages(array(
                    'parent' => $root,
                    'args' => $args
                ));
            },
            'products' => function($root, $args) {
                return $this->getRelatedProducts(array(
                    'parent' => $root,
                    'args' => $args
                ));
            },
            'attributes' => function($root, $args) {
                return $this->getAttributes(array(
                    'parent' => $root,
                    'args' => $args
                ));
            },
            'reviews' => function($root, $args) {
                return $this->load->resolver('store/review/get', array(
                    'parent' => $root,
                    'args' => $args
                ));
            },
            'options' => function($root, $args) {
                return $this->getOptions(array(
                    'parent' => $root,
                    'args' => $args
                ));
            }
        );

        return $product_info;
    }
    public function getList($args) {
        $this->load->model('store/product');

        $filter_data = array(
            'start' => ($args['page'] - 1) * $args['size'],
            'limit' => $args['size'],
            'sort'  => $args['sort'],
            'order' => $args['order'],
        );

        if ($filter_data['sort'] == 'id') {
            $filter_data['sort'] = 'p.ID';
        }

        if ($args['category_id'] !== 0) {
            $filter_data['filter_category_id'] = $args['category_id'];
        }

        if (!empty($args['ids'])) {
            $filter_data['filter_ids'] = $args['ids'];
        }

        if (!empty($args['special'])) {
            $filter_data['filter_special'] = true;
        }

        if (!empty($args['search'])) {
            $filter_data['filter_search'] = $args['search'];
        }

        $results = $this->model_store_product->getProducts($filter_data);

        $product_total = $this->model_store_product->getTotalProducts($filter_data);

        $products = [];

        foreach ($results as $product) {
            $products[] = $this->get(array( 'id' => $product->ID ));
        }

        return array(
            'content'          => $products,
            'first'            => $args['page'] === 1,
            'last'             => $args['page'] === ceil($product_total / $args['size']),
            'number'           => (int) $args['page'],
            'numberOfElements' => count($products),
            'size'             => (int) $args['size'],
            'totalPages'       => (int) ceil($product_total / $args['size']),
            'totalElements'    => (int) $product_total,
        );
    }
    public function getRelatedProducts($data) {
        $product = $data[0];
        $args = $data[1];

        $upsell_ids = $this->model_store_product->getProductRelated($product['id']);

        $upsell_ids = array_slice($upsell_ids, 0, $args['limit']);

        $products = array();

        foreach ($upsell_ids as $product_id) {
            $products[] = $this->get(array( 'id' => $product_id ));
        }


        return $products;
    }
    public function getAttributes($data) {
        $product = $data[0];
        $results = $this->model_store_product->getProductAttributes($product['id']);

        $attributes = array();

        foreach ($results as $attribute) {
            if (!$attribute['is_variation'] && $attribute['is_visible']) {
                $attributes[] = array(
                    'name'    => $attribute['name'],
                    'options' => explode('|', $attribute['value'])
                );
            }
        }

        return $attributes;
    }
    public function getOptions($data) {
        $this->load->model('store/option');
        $product = $data[0];

        $results = $this->model_store_product->getProductAttributes($product['id']);

        $options = array();


        foreach ($results as $attribute) {
            if ($attribute['is_variation'] && $attribute['is_visible']) {
                $option_values = array();
                if ($attribute['is_taxonomy']) {
                    $result_values = $this->model_store_product->getOptionValues($attribute['name']);
                    $name = $this->model_store_option->getOptionLabel($attribute['name']);

                    foreach ($result_values as $value) {
                        $option_values[] = array(
                            'id'   => $value->name,
                            'name' => $value->name
                        );
                    }
                } else {
                    $name = $attribute['name'];
                    $result_values = explode('|', $attribute['value']);
                    foreach ($result_values as $value) {
                        $option_values[] = array(
                            'id'   => $value,
                            'name' => $value
                        );
                    }
                }

                $options[] = array(
                    'id'     => 'attribute_' . sanitize_title($attribute['name']),
                    'name'   => $name,
                    'values' => $option_values
                );
            }
        }

        return $options;
    }
    public function getImages($data) {
        $product = $data[0];
        $args = $data[1];
        
        $image_ids = $this->model_store_product->getProductImages($product['id']);
        
        $image_ids = array_slice($image_ids, 0, $args['limit']);

        $images = array();

        foreach ($image_ids as $image_id) {
            $product_image      = wp_get_attachment_image_src($image_id, 'full');
            $thumb              = $product_image[0];
            $product_lazy_image = wp_get_attachment_image_src($image_id, array( 10, 10 ));
            $thumbLazy          = $product_lazy_image[0];
            $images[]           = array(
                'image'     => $thumb,
                'imageLazy' => $thumbLazy
            );
        }

        return $images;
    }
}