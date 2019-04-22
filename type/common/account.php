<?php

use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\InputObject\InputObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\BooleanType;

class TypeCommonAccount extends Type
{
    public function getMutations()
    {
        return array(
            'accountLogin'       => array(
                'type'    => $this->type(),
                'args'    => array(
                    'email'    => array(
                        'type' => new StringType(),
                    ),
                    'password' => array(
                        'type' => new StringType()
                    )
                ),
                'resolve' => function ( $store, $args ) {
                    return $this->load->resolver('common/account/login', $args );
                }
            ),
            'accountLogout'      => array(
                'type'    => new ObjectType(
                    array(
                        'name'        => 'LogoutResult',
                        'description' => 'LogoutResult',
                        'fields'      => array(
                            'status' => new BooleanType()
                        )
                    )
                ),
                'resolve' => function ( $store, $args ) {
                    return $this->load->resolver('common/account/logout', $args);
                }
            ),
            'accountRegister'    => array(
                'type'    => $this->type(),
                'args'    => array(
                    'customer' => array(
                        'type' => $this->inputType()
                    )
                ),
                'resolve' => function ( $store, $args ) {
                    return $this->register( $args );
                }
            ),
            'accountCheckLogged' => array(
                'type'    => new ObjectType(
                    array(
                        'name'        => 'LoggedResult',
                        'description' => 'LoggedResult',
                        'fields'      => array(
                            'status'   => new BooleanType(),
                            'customer' => $this->type()
                        )
                    )
                ),
                'resolve' => function ( $parent, $args ) {
                    return $this->load->resolver('common/account/isLogged', $args );
                }
            )
        );
    }

    public function type() {
        return new ObjectType(
            array(
                'name'        => 'Customer',
                'description' => 'Customer',
                'fields'      => array(
                    'id'        => new StringType(),
                    'firstName' => new StringType(),
                    'lastName'  => new StringType(),
                    'email'     => new StringType()
                )
            )
        );
    }

    public function inputType() {
        return new InputObjectType(
            array(
                'name'        => 'CustomerInput',
                'description' => 'CustomerInput',
                'fields'      => array(
                    'firstName' => new StringType(),
                    'lastName'  => new StringType(),
                    'email'     => new StringType(),
                    'password'  => new StringType()
                )
            )
        );
    }
}