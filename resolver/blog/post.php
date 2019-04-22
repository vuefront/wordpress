<?php

class ResolverBlogPost extends Resolver
{
    public function get($args) {
        $post = get_post($args['id']);

        $thumb     = get_the_post_thumbnail_url($post->ID, 'full');
        $thumbLazy = get_the_post_thumbnail_url($post->ID, array( 10, 10 ));

        return array(
            'id'               => $post->ID,
            'title'            => $post->post_title,
            'shortDescription' => $post->post_excerpt,
            'description'      => $post->post_content,
            'image'            => $thumb,
            'imageLazy'        => $thumbLazy
        );
    }

    public function getList($args) {
        $filter_data = array(
            'posts_per_page' => $args['size'],
            'offset'         => ($args['page'] - 1) * $args['size'],
            'orderby'        => $args['sort'],
            'order'          => $args['order']
        );

        if ($args['category_id'] !== 0) {
            $filter_data['category'] = $args['category_id'];
        }
        
        $results = get_posts($filter_data);


        unset($filter_data['posts_per_page']);
        unset($filter_data['offset']);

        $product_total = count(get_posts($filter_data));

        $posts = array();

        foreach ($results as $post) {
            $posts[] = $this->get(array( 'id' => $post->ID ));
        }

        return array(
            'content'          => $posts,
            'first'            => $args['page'] === 1,
            'last'             => $args['page'] === ceil($product_total / $args['size']),
            'number'           => (int) $args['page'],
            'numberOfElements' => count($posts),
            'size'             => (int) $args['size'],
            'totalPages'       => (int) ceil($product_total / $args['size']),
            'totalElements'    => (int) $product_total,
        );
    }
}