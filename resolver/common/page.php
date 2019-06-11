<?php

class ResolverCommonPage extends Resolver
{
    public function get($args) {
        $this->load->model('common/page');
        $page_info = $this->model_common_page->getPage($args['id']);

        $keyword = str_replace(get_site_url(), '', get_page_link($page_info->ID));
        $keyword = trim($keyword, '/?');
        $keyword = trim($keyword, '/');

        return array(
            'id' => $page_info->ID,
            'title' => $page_info->title,
            'name' => $page_info->title,
            'description' => $page_info->description,
            'sort_order' => (int)$page_info->sort_order,
            'keyword' => $keyword
        );
    }

    public function getList($args) {
        $this->load->model('common/page');
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
            $pages[] = $this->get( array( 'id' => $page->ID ) );
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