<?php

class ResolverBlogCategory extends Resolver
{
    public function get($data) {
        $category = get_term($data['id']);

        $thumb      = '';
        $thumbLazy = '';

        return array(
            'id'             => $category->term_id,
            'name'           => $category->name,
            'description'    => $category->description,
            'parent_id'      => (string) $category->parent,
            'image'          => $thumb,
            'imageLazy'      => $thumbLazy,
            'url'            => function($root, $args) {
                return $this->url(array(
                    'parent' => $root,
                    'args'   => $args
                ));
            },
            'categories'     => function($root, $args) {
                return $this->child(array(
                    'parent' => $root,
                    'args'   => $args
                ));
            }
        );
    }

    public function getList($args) {
        $filter_data = array(
            'number'  => $args['size'],
            'offset'  => ($args['page'] - 1) * $args['size'],
            'orderby' => $args['sort'],
            'order'   => $args['order']
        );

        if ($args['parent'] !== 0) {
            $filter_data['parent'] = $args['parent'];
        }

        $product_categories = get_terms('category', $filter_data);

        unset($filter_data['number']);
        unset($filter_data['offset']);

        $category_total = count(get_terms('category', $filter_data));

        $categories = array();

        foreach ($product_categories as $category) {
            $categories[] = $this->get(array( 'id' => $category->term_id ));
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
        $category = $data['parent'];
        $filter_data = array(
            'parent' => $category['id']
        );

        $blog_categories = get_terms('category', $filter_data);

        $categories = array();

        foreach ($blog_categories as $category) {
            $categories[] = $this->get(array( 'id' => $category->term_id ));
        }

        return $categories;
    }

    public function url($data) {
        $category_info = $data['parent'];
        $result = $data['args']['url'];

        $result = str_replace("_id", $category_info['id'], $result);
        $result = str_replace("_name", $category_info['name'], $result);

        return $result;
    }
}