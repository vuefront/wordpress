<?php

use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class TypeCommonPage extends Type
{
    public function getQuery()
    {
        return array(
            'page' => array(
                'type' => $this->type(),
                'args' => array(
                    'id' => array(
                        'type' => new IntType()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('common/page/get', $args);
                }
            ),
            'pagesList' => array(
                'type' => $this->load->type('common/pagination/type', $this->type()),
                'args' => array(
                    'page' => array(
                        'type' => new IntType(),
                        'defaultValue' => 1
                    ),
                    'size' => array(
                        'type' => new IntType(),
                        'defaultValue' => 10
                    ),
                    'search' => array(
                        'type' => new StringType(),
                        'defaultValue' => ''
                    ),
                    'sort' => array(
                        'type' => new StringType(),
                        'defaultValue' => "sort_order"
                    ),
                    'order' => array(
                        'type' => new StringType(),
                        'defaultValue' => 'ASC'
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->load->resolver('common/page/getList', $args);
                }
            )
        );
    }

    private function type()
    {
        return new ObjectType(array(
            'name' => 'Page',
            'description' => 'Page',
            'fields' => array(
                'id' => new IdType(),
                'title' => new StringType(),
                'description' => new StringType(),
                'sort_order' => new IntType(),
            )
        ));
    }
}