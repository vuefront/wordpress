<?php

class VFA_ResolverCommonZone extends VFA_Resolver
{
    private $codename = "vuefront";

    public function get($args)
    {
        return array();
    }

    public function getList($args)
    {

        $zones = [];

        $results = [];
        $zone_total = 1;

        foreach ($results as $product) {
            $zones[] = $this->get(array( 'id' => $product->ID ));
        }

        return array(
            'content'          => $zones,
            'first'            => $args['page'] === 1,
            'last'             => $args['page'] === ceil($zone_total / $args['size']),
            'number'           => (int) $args['page'],
            'numberOfElements' => count($zones),
            'size'             => (int) $args['size'],
            'totalPages'       => (int) ceil($zone_total / $args['size']),
            'totalElements'    => (int) $zone_total,
        );
    }
}