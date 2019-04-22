<?php

use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\FloatType;

class TypeBlogReview extends Type
{

    public function getMutations()
    {
        return array(
            'addBlogPostReview' => array(
                'type' => $this->load->type('blog/post/type'),
                'args' => array(
                    'id' => new IntType(),
                    'rating' => new FloatType(),
                    'author' => new StringType(),
                    'content' => new StringType()
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('blog/review/add', $args);
                }
            )
        );
    }
}