<?php

class ModelBlogPost extends Model {

	public function getPost( $category_id ) {
		global $wpdb;

		$sql = "SELECT 
            t.`term_id` AS 'ID',
            t.`name`,
            tt.`parent`,
            tt.`description`
        FROM
            `wp_terms` t 
            LEFT JOIN `wp_term_taxonomy` tt 
            ON tt.`term_id` = t.`term_id` 
        WHERE tt.`taxonomy` = 'category' and t.`term_id` = '" . (int) $category_id . "'";

		$sql .= " GROUP BY t.term_id";

		$result = $wpdb->get_row( $sql );

		return $result;
	}

	public function getPosts( $data = array() ) {
		global $wpdb;

		$sql = "SELECT 
		  p.ID,
		  p.`post_title` AS title,
		  p.`post_content` AS description,
		  p.`post_excerpt` AS shortDescription,
		  (SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = p.`ID` AND `meta_key` = '_thumbnail_id') AS image_id
		FROM 
		  `wp_posts` p 
		WHERE p.`post_type` = 'post' 
		  AND p.`post_status` = 'publish'";

		$implode = array();

		if ( isset( $data['filter_category_id'] ) ) {
			$implode[] = "'" . (int) $data['filter_category_id'] . "' IN (SELECT 
			    tt.`term_id` 
			  FROM
			    `wp_term_relationships` tr 
			    LEFT JOIN `wp_term_taxonomy` tt 
			      ON tr.`term_taxonomy_id` = tt.`term_taxonomy_id` 
			  WHERE tt.`taxonomy` = 'category' 
			    AND tr.`object_id` = p.`ID`)";
		}

		if ( count( $implode ) > 0 ) {
			$sql .= ' AND ' . implode( ' AND ', $implode );
		}

		$sql .= " GROUP BY ID";

		$sort_data = array(
			'ID'
		);

		if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY ID";
		}

		if ( isset( $data['order'] ) && ( $data['order'] == 'DESC' ) ) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if ( isset( $data['start'] ) || isset( $data['limit'] ) ) {
			if ( $data['start'] < 0 ) {
				$data['start'] = 0;
			}

			if ( $data['limit'] < 1 ) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		}

		$results = $wpdb->get_results( $sql );

		return $results;
	}

	public function getTotalPosts( $data = array() ) {
		global $wpdb;

		$sql = "SELECT count(*) as total 
		FROM 
		  `wp_posts` p 
		WHERE p.`post_type` = 'post' 
		  AND p.`post_status` = 'publish'";

		$implode = array();

		if ( isset( $data['filter_category_id'] ) ) {
			$implode[] = "'" . (int) $data['filter_category_id'] . "' IN (SELECT 
			    tt.`term_id` 
			  FROM
			    `wp_term_relationships` tr 
			    LEFT JOIN `wp_term_taxonomy` tt 
			      ON tr.`term_taxonomy_id` = tt.`term_taxonomy_id` 
			  WHERE tt.`taxonomy` = 'category' 
			    AND tr.`object_id` = p.`ID`)";
		}

		if ( count( $implode ) > 0 ) {
			$sql .= ' AND ' . implode( ' AND ', $implode );
		}

		$result = $wpdb->get_row( $sql );

		return $result->total;
	}
}