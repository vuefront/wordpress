<?php

use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\FloatType;

class TypeStoreReview extends Type
{
    public function getMutations()
    {
        return array(
            'addReview'  => array(
                'type'    => $this->load->type('store/product/type'),
                'args'    => array(
                    'id'      => new IntType(),
                    'rating'  => new FloatType(),
                    'author'  => new StringType(),
                    'content' => new StringType()
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('store/review/add', $args);
                }
                )
            );
    }
}
