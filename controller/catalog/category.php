<?php

use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

require_once __DIR__ . '/../../helpers/pagination.php';

class ControllerCatalogCategory
{
    public function getQuery()
    {
        return array(
            'category'       => array(
                'type'    => $this->getCategoryType(),
                'args'    => array(
                    'id' => array(
                        'type' => new IntType()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->getCategory($args);
                }
            ),
            'categoriesList' => array(
                'type'    => getPagination($this->getCategoryType()),
                'args'    => array(
                    'page'   => array(
                        'type'         => new IntType(),
                        'defaultValue' => 1
                    ),
                    'size'   => array(
                        'type'         => new IntType(),
                        'defaultValue' => 10
                    ),
                    'filter' => array(
                        'type'         => new StringType(),
                        'defaultValue' => ''
                    ),
                    'parent' => array(
                        'type'         => new IntType(),
                        'defaultValue' => 0
                    ),
                    'sort'   => array(
                        'type'         => new StringType(),
                        'defaultValue' => "sort_order"
                    ),
                    'order'  => array(
                        'type'         => new StringType(),
                        'defaultValue' => 'ASC'
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->getCategoryList($args);
                }
            )
        );
    }

    public function getCategory($args)
    {
        $category = get_term($args['id']);

        $image_id            = get_term_meta($category->term_id, 'thumbnail_id', true);

        if (!empty($image_id)) {
            $category_image      = wp_get_attachment_image_src($image_id, 'full');
            $category_lazy_image = wp_get_attachment_image_src($image_id, array( 10, 10 ));

            $thumb               = $category_image[0];
            $thumbLazy           = $category_lazy_image[0];
        } else {
            $thumb      = wc_placeholder_img_src('full');
            $thumbLazy = wc_placeholder_img_src(array( 10, 10 ));
        }

        return array(
            'id'          => $category->term_id,
            'name'        => $category->name,
            'description' => $category->description,
            'parent_id'   => (string) $category->parent,
            'image'       => $thumb,
            'imageLazy'   => $thumbLazy
        );
    }

    public function getCategoryList($args)
    {
        $filter_data = array(
            'orderby' => $args['sort'],
            'order'   => $args['order']
        );

        if ($args['parent'] != -1) {
            $filter_data['parent'] = $args['parent'];
        }

        if ($args['size'] != - 1) {
            $filter_data['number'] = $args['size'];
            $filter_data['offset'] = ($args['page'] - 1) * $args['size'];
        }

        $product_categories = get_terms('product_cat', $filter_data);

        unset($filter_data['number']);
        unset($filter_data['offset']);

        $category_total = count(get_terms('product_cat', $filter_data));

        $categories = array();

        foreach ($product_categories as $category) {
            $categories[] = $this->getCategory(array( 'id' => $category->term_id ));
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

    public function childCategories($category, $args) {
        $filter_data = array(
            'parent' => $category['id']
        );

        $product_categories = get_terms('product_cat', $filter_data);

        $categories = array();

        foreach ($product_categories as $category) {
            $categories[] = $this->getCategory(array( 'id' => $category->term_id ));
        }

        return $categories;
    }

    public function categoryUrl($parent, $args) {
        $category_info = $parent;
        $result = $args['url'];

        $result = str_replace("_id", $category_info['id'], $result);
        $result = str_replace("_name", $category_info['name'], $result);

        return $result;
    }

    private function getCategoryType($simple = false)
    {
        $fields = array();

        if (! $simple) {
            $fields = array(
                'categories' => array(
                    'type'    => new ListType($this->getCategoryType(true)),
                    'args'    => array(
                        'limit' => array(
                            'type'         => new IntType(),
                            'defaultValue' => 3
                        )
                    ),
                    'resolve' => function ($parent, $args) {
                        return $this->childCategories(
                            $parent,
                            $args
                        );
                    }
                )
            );
        }
        return new ObjectType(
            array(
            'name'        => 'Category',
            'description' => 'Category',
            'fields'      => array_merge(
                $fields,
                array(
                    'id'          => new IdType(),
                    'image'       => new StringType(),
                    'imageLazy'   => new StringType(),
                    'name'        => new StringType(),
                    'description' => new StringType(),
                    'parent_id'   => new StringType(),
                    'url' => array(
                        'type'    => new StringType,
                        'args'    => array(
                            'url' => array(
                                'type'         => new StringType(),
                                'defaultValue' => '_id'
                            )
                        ),
                        'resolve' => function ($parent, $args) {
                            return $this->categoryUrl($parent, $args);
                        }
                    )
                )
            )
        )
    );
    }
}
