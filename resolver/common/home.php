<?php

class VFA_ResolverCommonHome extends VFA_Resolver
{
    public function get() {
        return array(
            'meta' => array(
                'title' => get_option('blogname'),
                'description' => get_option('blogdescription'),
                'keyword' => ''
            )
        );
    }
}