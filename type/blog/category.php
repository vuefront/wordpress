<?php

use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class TypeBlogCategory extends Type
{
    public function getQuery()
    {
        return array(
            'categoryBlog'       => array(
                'type'    => $this->type(),
                'args'    => array(
                    'id' => array(
                        'type' => new IntType()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('blog/category/get', $args);
                }
            ),
            'categoriesBlogList' => array(
                'type'    => $this->load->type('common/pagination/type', $this->type()),
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
                    return $this->load->resolver('store/category/getList', $args);
                }
            )
        );
    }

    public function type($simple = false)
    {
        $fields = array();

        if (! $simple) {
            $fields = array(
                'categories' => array(
                    'type'    => new ListType($this->type(true)),
                    'args'    => array(
                        'limit' => array( 
                            'type'         => new IntType(),
                            'defaultValue' => 3
                        )
                    ),
                    'resolve' => function ($parent, $args) {
                        return $this->load->resolver('blog/category/child', array($parent, $args));
                    }
                )
            );
        }
        return new ObjectType(array(
            'name'        => 'categoryBlog',
            'description' => 'Blog Category',
            'fields'      => array_merge(
                $fields,
                array(
                    'id'          => new IdType(),
                    'name'        => new StringType(),
                    'description' => new StringType(),
                    'image'       => new StringType(),
                    'imageLazy'   => new StringType(),
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
                            return $this->load->resolver('blog/category/url', array($parent, $args));
                        }
                    )
                )
            )
        ));
    }
}