<?php

class ResolverCommonHome extends Resolver
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