<?php


use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\InputObject\InputObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\BooleanType;

require_once __DIR__ . '/../../helpers/pagination.php';
require_once __DIR__ . '/../../helpers/model.php';

class ControllerCommonPage
{

    private $model_common_page = false;

    public function __construct() {
        $this->model_common_page = getModel( 'common/page' );
    }

    public function getQuery()
    {
        return array(
            'page' => array(
                'type' => $this->pageType(),
                'args' => array(
                    'id' => array(
                        'type' => new IntType()
                    )
                ),
                'resolve' => function ($store, $args) {
                    return $this->getPage($args);
                }
            ),
            'pagesList' => array(
                'type' => getPagination($this->pageType()),
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
                    return $this->getPageList($args);
                }
            )
        );
    }

    private function pageType()
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

    public function getPage($args) {
        $page_info = $this->model_common_page->getPage($args['id']);

        return array(
            'id' => $page_info->ID,
            'title' => $page_info->title,
            'description' => $page_info->description,
            'sort_order' => (int)$page_info->sort_order
        );
    }

    public function getPageList($args) {
        $filter_data = array(
            'start' => ( $args['page'] - 1 ) * $args['size'],
            'limit' => $args['size'],
            'sort'  => $args['sort'],
            'order' => $args['order'],
        );

        if($filter_data['sort'] == 'id') {
            $filter_data['sort'] = 'p.ID';
        }

        if (!empty($args['search'])) {
            $filter_data['filter_search'] = $args['search'];
        }

        $results = $this->model_common_page->getPages( $filter_data );

        $page_total = $this->model_common_page->getTotalPages( $filter_data );

        $pages = array();

        foreach ( $results as $page ) {
            $pages[] = $this->getPage( array( 'id' => $page->ID ) );
        }

        return array(
            'content'          => $pages,
            'first'            => $args['page'] === 1,
            'last'             => $args['page'] === ceil( $page_total / $args['size'] ),
            'number'           => (int) $args['page'],
            'numberOfElements' => count( $pages ),
            'size'             => (int) $args['size'],
            'totalPages'       => (int) ceil( $page_total / $args['size'] ),
            'totalElements'    => (int) $page_total,
        );
    }
}