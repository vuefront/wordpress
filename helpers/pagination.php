<?php

use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\IntType;

function getPagination($type)
{
    return new ObjectType([
        'name' => (string)$type . 'Result',
        'description' => (string)$type . ' List',
        'fields' => [
            'content' => new ListType($type),
            'first' => new BooleanType(),
            'last' => new BooleanType(),
            'number' => new IntType(),
            'numberOfElements' => new IntType(),
            'size' => new IntType(),
            'totalPages' => new IntType(),
            'totalElements' => new IntType()

        ]
    ]);
}