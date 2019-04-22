<?php

use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class TypeStoreWishlist extends Type {

    public function getMutations()
    {
        $product_type = $this->load->type('store/product/type');
        return array(
            'addToWishlist'  => array(
                'type'    => new ListType($product_type),
                'args'    => array(
                    'id'       => array(
                        'type' => new IntType(),
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('store/wishlist/add', $args);
                }
            ),
            'removeWishlist' => array(
                'type'    => new ListType($product_type),
                'args'    => array(
                    'id' => array(
                        'type' => new StringType()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('store/wishlist/remove', $args);
                }
            )
        );
    }

    public function getQuery()
    {
        $product_type = $this->load->type('store/product/type');
        return array(
            'wishlist' => array(
                'type' => new ListType($product_type),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('store/wishlist/getList', $args);
                }
            )
        );
    }
}