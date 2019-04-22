<?php

use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\InputObject\InputObjectType;

class TypeStoreCart extends Type {
    public function getMutations()
    {
        $product_type = $this->load->type('store/product/type');
        return array(
            'addToCart'  => array(
                'type'    => $this->type(),
                'args'    => array(
                    'id'       => array(
                        'type' => new IntType(),
                    ),
                    'quantity' => array(
                        'type'         => new IntType(),
                        'defaultValue' => 1
                    ),
                    'options'  => array(
                        'type'         => new ListType($this->optionType()),
                        'defaultValue' => array()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('store/cart/add', $args);
                }
            ),
            'updateCart' => array(
                'type'    => $this->type(),
                'args'    => array(
                    'key'      => array(
                        'type' => new StringType()
                    ),
                    'quantity' => array(
                        'type'         => new IntType(),
                        'defaultValue' => 1
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('store/cart/update', $args);
                }
            ),
            'removeCart' => array(
                'type'    => $this->type(),
                'args'    => array(
                    'key' => array(
                        'type' => new StringType()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('store/cart/remove', $args);
                }
            ),
        );
    }

    public function getQuery()
    {
        return array(
            'cart'         => array(
                'type'    => $this->type(),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('store/cart/get', $args);
                }
            ),
        );
    }

    public function type()
    {
        return new ObjectType(
            array(
                'name'        => 'Cart',
                'description' => 'Cart',
                'fields'      => array(
                    'products' => new ListType($this->productType())
                )
            )
        );
    }

    public function productType()
    {
        $product_type = $this->load->type('store/product/type');
        return new ObjectType(
            array(
                'name'        => 'CartProduct',
                'description' => 'CartProduct',
                'fields'      => array(
                    'key'      => new StringType(),
                    'product'  => $product_type,
                    'quantity' => new IntType(),
                    'total'    => new StringType()
                )
            )
        );
    }

    private function optionType()
    {
        return new InputObjectType(
            array(
                'name'        => 'CartOption',
                'description' => 'CartOption',
                'fields'      => array(
                    'id'    => new StringType(),
                    'value' => new StringType()
                )
            )
        );
    }
}