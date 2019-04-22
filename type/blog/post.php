<?php

use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\StringType;

class TypeBlogPost extends Type
{
    public function getQuery()
    {
        return array(
            'post'      => array(
                'type'    => $this->type(),
                'args'    => array(
                    'id' => array(
                        'type' => new IntType()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('blog/post/get',$args);
                }
            ),
            'postsList' => array(
                'type'    => $this->load->type('common/pagination/type', $this->type()),
                'args'    => array(
                    'page'        => array(
                        'type'         => new IntType(),
                        'defaultValue' => 1
                    ),
                    'size'        => array(
                        'type'         => new IntType(),
                        'defaultValue' => 10
                    ),
                    'filter'      => array(
                        'type'         => new StringType(),
                        'defaultValue' => ''
                    ),
                    'category_id' => array(
                        'type'         => new IntType(),
                        'defaultValue' => 0
                    ),
                    'sort'        => array(
                        'type'         => new StringType(),
                        'defaultValue' => "sort_order"
                    ),
                    'order'       => array(
                        'type'         => new StringType(),
                        'defaultValue' => 'ASC'
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('blog/post/getList', $args);
                }
            )
        );
    }

    public function type()
    {
        return new ObjectType(array(
            'name'        => 'Post',
            'description' => 'Blog Post',
            'fields'      => array(
                'id'               => new IdType(),
                'title'            => new StringType(),
                'shortDescription' => new StringType(),
                'description'      => new StringType(),
                'image'            => new StringType(),
                'imageLazy'        => new StringType(),
                'reviews'          => array(
                    'type'    => new ListType(
                        new ObjectType(
                            array(
                                'name'   => 'postReview',
                                'fields' => array(
                                    'author'       => new StringType(),
                                    'author_email' => new StringType(),
                                    'content'      => new StringType(),
                                    'created_at'   => new StringType(),
                                    'rating'       => new FloatType()
                                )
                            )
                        )
                    ),
                    'resolve' => function ( $parent, $args ) {
                        return $this->load->resolver('blog/review/get', array($parent, $args) );
                    }
                ),
            )
        ));
    }
}