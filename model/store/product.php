<?php

class ModelStoreProduct
{
    public function getProduct($product_id)
    {
        global $wpdb;

        $sql = "SELECT 
            p.ID, 
            p.post_title as name, 
            p.post_content as description, 
            p.post_excerpt as short_description, 
            ps2.meta_value as model, 
            (pr.meta_value + 0) as rating, 
            pss.meta_value as stock_status,
            (pm.meta_value + 0) as price, 
            (ps.meta_value + 0) as special, 
            pt.meta_value as image_id,
            (SELECT t.slug FROM wp_term_relationships rel
            LEFT JOIN wp_term_taxonomy tax ON tax.term_taxonomy_id = rel.term_taxonomy_id
            LEFT JOIN wp_terms t ON t.term_id = tax.term_id WHERE rel.`object_id` = p.ID AND tax.`taxonomy` = 'product_type') AS type
            FROM wp_posts p
            LEFT JOIN wp_postmeta pm ON (pm.post_id = p.ID AND pm.meta_key = '_regular_price')
            LEFT JOIN wp_postmeta ps ON (ps.post_id = p.ID AND ps.meta_key = '_sale_price')
            LEFT JOIN wp_postmeta pt ON (pt.post_id = p.ID AND pt.meta_key = '_thumbnail_id')
            LEFT JOIN wp_postmeta pr ON (pr.post_id = p.ID AND pr.meta_key = '_wc_average_rating')
            LEFT JOIN wp_postmeta ps2 ON (ps2.post_id = p.ID AND ps2.meta_key = '_sku')
            LEFT JOIN wp_postmeta pss ON (pss.post_id = p.ID AND pss.meta_key = '_stock_status')
            WHERE p.ID = '".(int)$product_id."'";

        $result = $wpdb->get_row($sql);

        return $result;
    }

    public function getVariationLowPrice($product_id)
    {
        global $wpdb;

        $sql ="SELECT 
        p.`ID`,
        (pm.`meta_value` + 0) AS price
       FROM
         wp_posts p 
         LEFT JOIN wp_postmeta pm 
           ON (
             pm.post_id = p.ID 
             AND pm.meta_key = '_regular_price'
           ) 
       WHERE p.`post_parent` = '".(int)$product_id."' 
       ORDER BY price ASC
       LIMIT 0, 1";

        $result = $wpdb->get_row($sql);

        return $result->ID;
    }

    public function getProducts($data = array())
    {
        global $wpdb;
        $sql = "SELECT 
            p.ID, 
            p.post_title, 
            (p.menu_order + 0) as sort_order,
            (pm.meta_value + 0) AS price,
            (ps.meta_value + 0) AS special,
            (pr.meta_value + 0) AS rating,
            p.post_date AS date_added,
            ps2.meta_value as model
        FROM wp_posts p
        LEFT JOIN wp_postmeta pm ON (pm.post_id = p.ID AND pm.meta_key = '_regular_price')
        LEFT JOIN wp_postmeta ps ON (ps.post_id = p.ID AND ps.meta_key = '_sale_price')
        LEFT JOIN wp_postmeta pr ON (pr.post_id = p.ID AND pr.meta_key = '_wc_average_rating')
        LEFT JOIN wp_postmeta ps2 ON (ps2.post_id = p.ID AND ps2.meta_key = '_sku')
        WHERE p.post_type = 'product' AND p.post_status = 'publish'";

        $implode = array();

        if (!empty($data['filter_ids'])) {
            $implode[] = "p.ID in ('".implode("' , '", $data['filter_ids'])."')";
        }

        if (!empty($data['filter_category_id'])) {
            $implode[] = "'".(int)$data['filter_category_id']."' IN (SELECT t.`term_id` FROM wp_term_relationships rel
            LEFT JOIN wp_term_taxonomy tax ON tax.term_taxonomy_id = rel.term_taxonomy_id
            LEFT JOIN wp_terms t ON t.term_id = tax.term_id
            WHERE rel.`object_id` = p.ID AND tax.`taxonomy` = 'product_cat')";
        }

        if (!empty($data['filter_special'])) {
            $implode[] = "(ps.meta_value IS NOT NULL AND (ps.meta_value + 0) > 0)";
        }

        if (!empty($data['filter_search'])) {
            $implode[] = "(p.post_title LIKE '%".$data['filter_search']."%' 
            OR p.post_content LIKE '%".$data['filter_search']."%'
            OR ps2.meta_value LIKE '%".$data['filter_search']."%')";
        }

        if (count($implode) > 0) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $sql .= " GROUP BY p.ID";

        $sort_data = array(
            'p.ID',
            'price',
            'special',
            'rating',
            'date_added',
            'model',
            'sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY p.ID";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $results = $wpdb->get_results($sql);

        return $results;
    }

    public function getTotalProducts($data = array())
    {
        global $wpdb;

        $sql = "SELECT count(*) as total 
        from wp_posts p
        LEFT JOIN wp_postmeta ps ON (ps.post_id = p.ID AND ps.meta_key = '_sale_price') 
        LEFT JOIN wp_postmeta ps2 ON (ps2.post_id = p.ID AND ps2.meta_key = '_sku')
        where p.post_type='product' AND post_status = 'publish'";

        $implode = array();

        if (!empty($data['filter_ids'])) {
            $implode[] = "p.ID in ('".implode("' , '", $data['filter_ids'])."')";
        }

        if (!empty($data['filter_category_id'])) {
            $implode[] = "'".(int)$data['filter_category_id']."' IN (SELECT t.`term_id` FROM wp_term_relationships rel
            LEFT JOIN wp_term_taxonomy tax ON tax.term_taxonomy_id = rel.term_taxonomy_id
            LEFT JOIN wp_terms t ON t.term_id = tax.term_id
            WHERE rel.`object_id` = p.ID AND tax.`taxonomy` = 'product_cat')";
        }

        if (!empty($data['filter_special'])) {
            $implode[] = "(ps.meta_value IS NOT NULL AND (ps.meta_value + 0) > 0)";
        }

        if (!empty($data['filter_search'])) {
            $implode[] = "(p.post_title LIKE '%".$data['filter_search']."%' 
            OR p.post_content LIKE '%".$data['filter_search']."%'
            OR ps2.meta_value LIKE '%".$data['filter_search']."%')";
        }

        if (count($implode) > 0) {
            $sql .= ' AND ' . implode(' AND ', $implode);
        }

        $result = $wpdb->get_row($sql);

        return $result->total;
    }
}
