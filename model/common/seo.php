<?php


class VFA_ModelCommonSeo extends VFA_Model {
    public function get_category_by_url($url) {
        foreach( (get_terms()) as $category) {
            $keyword = str_replace(get_site_url(), '', get_term_link((int)$category->term_id));
            $keyword = trim($keyword, '/?');
            $keyword = trim($keyword, '/');

            if ( '/' . $keyword == $url )
                
                return array('id' => $category->term_id, 'slug' => $category->slug, 'type' => $category->taxonomy);
        }
        return false;
    }
    public function searchKeyword($url) {
        $search = url_to_postid($url);
        $type = '';

        if ($search != 0) {
            if (get_post_type($search) == 'post') {
                $type = 'post';
            } else if (get_post_type($search) == 'page') {
                $type = 'page';
            } else {
                $type = 'product';
            }
        } else {
            $search = $this->get_category_by_url($url);
            if ($search) {
                if ($search['type'] == 'category') {
                    $type = 'blog-category';
                    $search = $search['id'];
                } else if($search['type'] == 'pwb-brand') {
                    $type = 'manufacturer';
                    $search = $search['slug'];
                } else {
                    $type='category';
                    $search = $search['id'];
                }
            } else {
                $search = '';
            }

        }

        return array(
            'id' => $search,
            'type' => $type,
            'url' => $url
        );
    }
}