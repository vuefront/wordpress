<?php

use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\BooleanType;

class TypeStoreProduct extends Type
{
    public function getQuery()
    {
        return array(
            'product'      => array(
                'type'    => $this->type(),
                'args'    => array(
                    'id' => array(
                        'type' => new IntType()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('store/product/get', $args);
                }
            ),
            'productsList' => array(
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
                    'special'     => array(
                        'type'         => new BooleanType(),
                        'defaultValue' => false
                    ),
                    'search'      => array(
                        'type'         => new StringType(),
                        'defaultValue' => ''
                    ),
                    'ids'         => array(
                        'type'         => new ListType(new IntType()),
                        'defaultValue' => array()
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
                    return $this->load->resolver('store/product/getList', $args);
                }
            )
        );
    }

    public function optionValueType()
    {
        return new ObjectType(
            array(
                'name'        => 'OptionValue',
                'description' => 'CartProduct',
                'fields'      => array(
                    'id'   => new StringType(),
                    'name' => new StringType()
                )
            )
        );
    }

    public function type($simple = false)
    {
        $fields = array();

        if (! $simple) {
            $fields = array(
                'products' => array(
                    'type'    => new ListType($this->type(true)),
                    'args'    => array(
                        'limit' => array(
                            'type'         => new IntType(),
                            'defaultValue' => 3
                        )
                    ),
                    'resolve' => function ($parent, $args) {
                        return $this->load->resolver('store/product/getRelatedProducts', array($parent, $args));
                    }
                )
            );
        }

        return new ObjectType(
            array(
                'name'        => 'Product',
                'description' => 'Product',
                'fields'      => array_merge(
                    $fields,
                    array(
                        'id'               => new IdType(),
                        'image'            => new StringType(),
                        'imageLazy'        => new StringType(),
                        'name'             => new StringType(),
                        'shortDescription' => new StringType(),
                        'description'      => new StringType(),
                        'model'            => new StringType(),
                        'price'            => new StringType(),
                        'special'          => new StringType(),
                        'tax'              => new StringType(),
                        'minimum'          => new IntType(),
                        'stock'            => new BooleanType(),
                        'rating'           => new FloatType(),
                        'attributes'       => array(
                            'type'    => new ListType(
                                new ObjectType(
                                    array(
                                        'name'   => 'productAttribute',
                                        'fields' => array(
                                            'name'    => new StringType(),
                                            'options' => new ListType(new StringType())
                                        )
                                    )
                                )
                            ),
                            'resolve' => function ($parent, $args) {
                                return $this->load->resolver('store/product/getAttributes', array($parent, $args));
                            }
                        ),
                        'reviews'          => array(
                            'type'    => new ListType(
                                new ObjectType(
                                    array(
                                        'name'   => 'productReview',
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
                            'resolve' => function ($parent, $args) {
                                return $this->load->resolver('store/review/get', array($parent, $args));
                            }
                        ),
                        'options'          => array(
                            'type'    => new ListType(
                                new ObjectType(
                                    array(
                                        'name'   => 'productOption',
                                        'fields' => array(
                                            'id'     => new StringType(),
                                            'name'   => new StringType(),
                                            'values' => new ListType($this->optionValueType())
                                        )
                                    )
                                )
                            ),
                            'resolve' => function ($parent, $args) {
                                return $this->load->resolver('store/product/getOptions', array($parent, $args));
                            }
                        ),
                        'images'           => array(
                            'type'    => new ListType(
                                new ObjectType(
                                    array(
                                        'name'   => 'productImage',
                                        'fields' => array(
                                            'image'     => new StringType(),
                                            'imageLazy' => new StringType()
                                        )
                                    )
                                )
                            ),
                            'args'    => array(
                                'limit' => array(
                                    'type'         => new IntType(),
                                    'defaultValue' => 3
                                )
                            ),
                            'resolve' => function ($parent, $args) {
                                return  $this->load->resolver('store/product/getImages', array($parent, $args));
                            }
                        )
                    )
                )
            )
        );
    }
}
