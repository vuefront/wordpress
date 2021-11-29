<?php


class VFA_ModelCommonSeo extends VFA_Model {
    public function addUrl($url, $type, $id)
    {
        global $wpdb;

        $sql = 'SELECT *
        FROM
            `'.$wpdb->prefix.'vuefront_url` 
        WHERE url LIKE \''.$url.'\'';

        $result = $wpdb->get_row( $sql );
        if (!$result) {
            $wpdb->insert( $wpdb->prefix.'vuefront_url' , array("url" => $url, "id" => $id, "type" => $type));
        }
    }
    public function get_category_by_url($url) {
        foreach( (get_terms()) as $category) {
            $keyword = str_replace(get_site_url(), '', get_term_link((int)$category->term_id));
            $keyword = trim($keyword, '/?');
            $keyword = trim($keyword, '/');
            var_dump($keyword);

            if ( $keyword == $url ) {
                return array('id' => $category->term_id, 'slug' => $category->slug, 'type' => $category->taxonomy);
            }
            if ( '/' . $keyword == $url ) {
                return array('id' => $category->term_id, 'slug' => $category->slug, 'type' => $category->taxonomy);
            }
            if ( '/?' . $keyword == $url ) {
                return array('id' => $category->term_id, 'slug' => $category->slug, 'type' => $category->taxonomy);
            }

        }
        return false;
    }
    public function searchKeyword($url) {

        global $wpdb;

        $sql = 'SELECT *
        FROM
            `'.$wpdb->prefix.'vuefront_url` 
        WHERE url LIKE \''.$url.'\'';

        $result = $wpdb->get_row( $sql );
        if (!$result) {
            return array(
                'id' => '',
                'type' => '',
                'url' => $url
            );
        }
        return array(
            'id' => $result->id,
            'type' => $result->type,
            'url' => $url
        );
    }
}