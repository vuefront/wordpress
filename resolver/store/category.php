<?php

class VFA_ResolverStoreCategory extends VFA_Resolver {
    public function get($args) {
    	$this->load->model('store/category');

    	$category_info = $this->model_store_category->getCategory($args['id']);

        if (empty($category_info)) {
            return array();
        }

        if (!empty($category_info->image_id)) {
            $category_image      = wp_get_attachment_image_src($category_info->image_id, 'full');
            $category_lazy_image = wp_get_attachment_image_src($category_info->image_id, array( 10, 10 ));

            $thumb               = $category_image[0];
            $thumbLazy           = $category_lazy_image[0];
        } else {
            $thumb      = '';
            $thumbLazy = '';
        }

        $keyword = str_replace(get_site_url(), '', get_term_link((int)$category_info->ID));
        $keyword = trim($keyword, '/?');
        $keyword = trim($keyword, '/');

        return array(
            'id'          => $category_info->ID,
            'name'        => $category_info->name,
            'description' => $category_info->description,
            'parent_id'   => (string) $category_info->parent,
            'image'       => $thumb,
            'imageLazy'   => $thumbLazy,
            'meta'           => array(
                'title' => $category_info->name,
                'description' => $category_info->description,
                'keyword' => ''
            ),
            'url' => function($root, $args) {
                return $this->url(array(
                    'parent' => $root,
                    'args' => $args
                ));
            },
            'categories' => function($root, $args) {
                return $this->child(array(
                    'parent' => $root,
                    'args' => $args
                ));
            },
            'keyword' => $keyword
        );
    }

    public function getList($args) {
    	$this->load->model('store/category');
        $filter_data = array(
            'sort' => $args['sort'],
            'order'   => $args['order']
        );
        if ($args['parent'] != -1) {
            $filter_data['filter_parent_id'] = $args['parent'];
        }

        if (!empty($args['search'])) {
            $filter_data['filter_search'] = $args['search'];
        }

        if ($args['top']) {
            $filter_data['filter_parent_id'] = $args['top']? 0 : 1;
        }

        if ($args['size'] != - 1) {
            $filter_data['start'] = ($args['page'] - 1) * $args['size'];
            $filter_data['limit'] = $args['size'];
        }

        $product_categories = $this->model_store_category->getCategories($filter_data);
        $category_total = $this->model_store_category->getTotalCategories($filter_data);

        $categories = array();

        foreach ($product_categories as $category) {
            $categories[] = $this->get(array( 'id' => $category->ID ));
        }

        return array(
            'content'          => $categories,
            'first'            => $args['page'] === 1,
            'last'             => $args['page'] === ceil($category_total / $args['size']),
            'number'           => (int) $args['page'],
            'numberOfElements' => count($categories),
            'size'             => (int) $args['size'],
            'totalPages'       => (int) ceil($category_total / $args['size']),
            'totalElements'    => (int) $category_total,
        );
    }

    public function child($data) {
        $this->load->model('store/category');
        $category = $data['parent'];
        $filter_data = array(
            'filter_parent_id' => $category['id']
        );

        $product_categories = $this->model_store_category->getCategories($filter_data);

        $categories = array();

        foreach ($product_categories as $category) {
            $categories[] = $this->get(array( 'id' => $category->ID ));
        }

        return $categories;
    }

    public function url($data) {
        $category_info = $data['parent'];
        $result = $data['args']['url'];

        $result = str_replace("_id", $category_info['id'], $result);
        $result = str_replace("_name", $category_info['name'], $result);

        $keyword = str_replace(get_site_url(), '', get_term_link((int)$category_info['id']));
        $keyword = trim($keyword, '/?');
        $keyword = trim($keyword, '/');

        if($keyword != '') {
            $result = '/'.$keyword;
            $this->load->model('common/seo');
            $this->model_common_seo->addUrl($result, 'category', $category_info['id']);
        }

        return $result;
    }
}