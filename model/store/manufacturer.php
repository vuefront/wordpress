<?php

class VFA_ModelStoreManufacturer extends VFA_Model
{

    public function getManufacturer($id) {
        global $wpdb;

        $sql = "SELECT *,
        (SELECT 
            `meta_value` 
            FROM
            `".$wpdb->prefix."termmeta` 
            WHERE `term_id` = t.`term_id` 
            AND meta_key = 'pwb_brand_image') AS 'image_id',
            (SELECT 
            `meta_value` 
            FROM
            `".$wpdb->prefix."termmeta` 
            WHERE `term_id` = t.`term_id` 
            AND meta_key = 'order') AS 'sort_order' 
        FROM  wp_term_taxonomy tax 
        LEFT JOIN wp_terms t ON t.term_id = tax.term_id 
        WHERE tax.`taxonomy` = 'pwb-brand' AND t.slug='".$id."'";

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            set_transient(md5($sql), $result, 300);
        }

        return $result;
    }

    public function getManufacturers($data) {
        global $wpdb;

        $sql = "SELECT *,
         t.`term_id` AS 'ID',
        (SELECT 
            `meta_value` 
            FROM
            `".$wpdb->prefix."termmeta` 
            WHERE `term_id` = t.`term_id` 
            AND meta_key = 'pwb_brand_image') AS 'image_id',
            (SELECT 
            `meta_value` 
            FROM
            `".$wpdb->prefix."termmeta` 
            WHERE `term_id` = t.`term_id` 
            AND meta_key = 'order') AS 'sort_order' 
        FROM  wp_term_taxonomy tax 
        LEFT JOIN wp_terms t ON t.term_id = tax.term_id 
        WHERE tax.`taxonomy` = 'pwb-brand'";

        if (isset($data['filter_name'])) {
            $implode[] = "t.`name`  LIKE '%".$data['filter_name']."%'";
        }

		if ( count( $implode ) > 0 ) {
			$sql .= ' AND ' . implode( ' AND ', $implode );
		}

		$sql .= " GROUP BY t.term_id";

		$sort_data = array(
			'ID',
			'sort_order'
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

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_results( $sql );
            set_transient(md5($sql), $result, 300);
        }

        return $result;
    }

    public function getTotalManufacturers($data) {
        global $wpdb;

        $sql = "SELECT count(*) as total
        FROM  wp_term_taxonomy tax 
        LEFT JOIN wp_terms t ON t.term_id = tax.term_id 
        WHERE tax.`taxonomy` = 'pwb-brand'";

        $implode = array();

        if (isset($data['filter_name'])) {
            $implode[] = "t.`name`  LIKE '%".$data['filter_name']."%'";
        }


		if ( count( $implode ) > 0 ) {
			$sql .= ' AND ' . implode( ' AND ', $implode );
        }

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            set_transient(md5($sql), $result, 300);
        }

        return $result->total;
    }
}