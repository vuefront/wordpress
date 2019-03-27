<?php


use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

require_once __DIR__ . '/../../helpers/pagination.php';

class ControllerBlogPost {
	public function getQuery() {
		return array(
			'post'      => array(
				'type'    => $this->getPostType(),
				'args'    => array(
					'id' => array(
						'type' => new IntType()
					)
				),
				'resolve' => function ( $store, $args ) {
					return $this->getPost( $args );
				}
			),
			'postsList' => array(
				'type'    => getPagination( $this->getPostType() ),
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
				'resolve' => function ( $store, $args ) {
					return $this->getPostList( $args );
				}
			)
		);
	}

	public function getPost( $args ) {
		$post = get_post( $args['id'] );

		$thumb     = get_the_post_thumbnail_url( $post->ID, 'full' );
		$thumbLazy = get_the_post_thumbnail_url( $post->ID, array( 10, 10 ) );

		return array(
			'id'               => $post->ID,
			'title'            => $post->post_title,
			'shortDescription' => $post->post_excerpt,
			'description'      => $post->post_content,
			'image'            => $thumb,
			'imageLazy'        => $thumbLazy
		);

	}

	public function getPostList( $args ) {

		$filter_data = array(
			'posts_per_page' => $args['size'],
			'offset'         => ( $args['page'] - 1 ) * $args['size'],
			'orderby'        => $args['sort'],
			'order'          => $args['order']
		);

		if ( $args['category_id'] !== 0 ) {
			$filter_data['category'] = $args['category_id'];
		}
		
		$results = get_posts( $filter_data );


		unset( $filter_data['posts_per_page'] );
		unset( $filter_data['offset'] );

		$product_total = count( get_posts( $filter_data ) );

		$posts = [];

		foreach ( $results as $post ) {
			$posts[] = $this->getPost( array( 'id' => $post->ID ) );
		}

		return array(
			'content'          => $posts,
			'first'            => $args['page'] === 1,
			'last'             => $args['page'] === ceil( $product_total / $args['size'] ),
			'number'           => (int) $args['page'],
			'numberOfElements' => count( $posts ),
			'size'             => (int) $args['size'],
			'totalPages'       => (int) ceil( $product_total / $args['size'] ),
			'totalElements'    => (int) $product_total,
		);
	}

	private function getPostType() {
		return new ObjectType( array(
			'name'        => 'Post',
			'description' => 'Blog Post',
			'fields'      => array(
				'id'               => new IdType(),
				'title'            => new StringType(),
				'shortDescription' => new StringType(),
				'description'      => new StringType(),
				'image'            => new StringType(),
				'imageLazy'        => new StringType()
			)
		) );
	}
}