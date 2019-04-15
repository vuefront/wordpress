<?php


use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\StringType;

require_once __DIR__ . '/../../helpers/pagination.php';

class ControllerBlogPost
{
    public function getQuery()
    {
        return array(
            'post'      => array(
                'type'    => $this->getPostType(),
                'args'    => array(
                    'id' => array(
                        'type' => new IntType()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->getPost($args);
                }
            ),
            'postsList' => array(
                'type'    => getPagination($this->getPostType()),
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
                    return $this->getPostList($args);
                }
            )
        );
    }
    
    public function getMutations()
    {
        return array(
            'addBlogPostReview' => array(
                'type' => $this->getPostType(),
                'args' => array(
                    'id' => new IntType(),
                    'rating' => new FloatType(),
                    'author' => new StringType(),
                    'content' => new StringType()
                ),
                'resolve' => function ($store, $args) {
                    return $this->addReview($args);
                }
            )
        );
    }

    public function getPost($args)
    {
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

    public function getPostList($args)
    {
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
            $posts[] = $this->getPost(array( 'id' => $post->ID ));
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

    public function getPostReviews($post, $args) {
        $result  = get_comments( array( 'post_type' => 'post', 'post_id' => $post['id'] ) );

        $comments = array();


        foreach ( $result as $comment ) {
            $comments[] = array(
                'author'       => $comment->comment_author,
                'author_email' => $comment->comment_author_email,
                'created_at'   => $comment->comment_date,
                'content'      => $comment->comment_content,
                'rating'       => (float) get_comment_meta( $comment->comment_ID, 'rating', true )
            );
        }

        return $comments;
    }

    private function getPostType()
    {
        return new ObjectType(array(
            'name'        => 'Post',
            'description' => 'Blog Post',
            'fields'      => array(
                'id'               => new IdType(),
                'title'            => new StringType(),
                'shortDescription' => new StringType(),
                'description'      => new StringType(),
                'image'            => new StringType(),
                'imageLazy'        => new StringType(),
                'reviews'          => array(
                    'type'    => new ListType(
                        new ObjectType(
                            array(
                                'name'   => 'postReview',
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
                    'resolve' => function ( $parent, $args ) {
                        return $this->getPostReviews( $parent, $args );
                    }
                ),
            )
        ));
    }

    public function addReview( $args ) {
        $time = current_time( 'mysql' );

        $data = array(
            'comment_post_ID' => $args['id'],
            'comment_author'  => $args['author'],
            'comment_content' => $args['content'],
            'comment_date'    => $time,
        );

        $comment_id = wp_insert_comment( $data );

        add_comment_meta( $comment_id, 'rating', $args['rating'] );

        return $this->getPost( $args );
    }
}
