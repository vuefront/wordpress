<?php

class VFA_ModelStoreProduct extends VFA_Model
{
    public function getProductRelated($product_id)
    {
        global $wpdb;

        $sql = "SELECT pm.`meta_value` AS related FROM `".$wpdb->prefix."postmeta` pm WHERE pm.`post_id` = '".(int)$product_id."' AND pm.`meta_key` = '_upsell_ids'";

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            set_transient(md5($sql), $result, 300);
        }

        $product_data = unserialize($result->related);

        return $product_data;
    }

    public function getProductImages($product_id)
    {
        global $wpdb;

        $sql = "SELECT pm.`meta_value` AS images FROM `".$wpdb->prefix."postmeta` pm WHERE pm.`post_id` = '".(int)$product_id."' AND pm.`meta_key` = '_product_image_gallery'";

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            set_transient(md5($sql), $result, 300);
        }

        $product_data = !empty($result->images) ? explode(',', $result->images) : array();

        return $product_data;
    }

    public function getProductAttributes($product_id)
    {
        global $wpdb;

        $sql = "SELECT pm.`meta_value` AS attributes FROM `".$wpdb->prefix."postmeta` pm WHERE pm.`post_id` = '".(int)$product_id."' AND pm.`meta_key` = '_product_attributes'";

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            set_transient(md5($sql), $result, 300);
        }

        $attribute_data = unserialize($result->attributes);

        return $attribute_data;
    }

    public function getOptionValues($taxonomy) {
        global $wpdb;
        
        $sql = "SELECT 
        t.`name`,
        t.`slug`
    FROM
        `".$wpdb->prefix."term_taxonomy`  tt
        LEFT JOIN `".$wpdb->prefix."terms` t ON t.`term_id` = tt.`term_id`
    WHERE tt.taxonomy = '".$taxonomy."' ";

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_results( $sql );
            set_transient(md5($sql), $result, 300);
        }

        return $result;
    }

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
            (pvm.meta_value + 0) as price_variation, 
            (pm.meta_value + 0) as price, 
            (ps.meta_value + 0) as special, 
            pt.meta_value as image_id,
            (SELECT t.slug FROM wp_term_relationships rel
            LEFT JOIN wp_term_taxonomy tax ON tax.term_taxonomy_id = rel.term_taxonomy_id
            LEFT JOIN wp_terms t ON t.term_id = tax.term_id WHERE rel.`object_id` = p.ID AND tax.`taxonomy` = 'product_type') AS type,
            (SELECT t.slug FROM wp_term_relationships rel
            LEFT JOIN wp_term_taxonomy tax ON tax.term_taxonomy_id = rel.term_taxonomy_id
            LEFT JOIN wp_terms t ON t.term_id = tax.term_id WHERE rel.`object_id` = p.ID AND tax.`taxonomy` = 'pwb-brand') AS manufacturer_id
            FROM wp_posts p
            LEFT JOIN wp_postmeta pm ON (pm.post_id = p.ID AND pm.meta_key = '_regular_price')
            LEFT JOIN wp_postmeta pvm ON (pvm.post_id = p.ID AND pvm.meta_key = '_price')
            LEFT JOIN wp_postmeta ps ON (ps.post_id = p.ID AND ps.meta_key = '_sale_price')
            LEFT JOIN wp_postmeta pt ON (pt.post_id = p.ID AND pt.meta_key = '_thumbnail_id')
            LEFT JOIN wp_postmeta pr ON (pr.post_id = p.ID AND pr.meta_key = '_wc_average_rating')
            LEFT JOIN wp_postmeta ps2 ON (ps2.post_id = p.ID AND ps2.meta_key = '_sku')
            LEFT JOIN wp_postmeta pss ON (pss.post_id = p.ID AND pss.meta_key = '_stock_status')
            WHERE p.ID = '".(int)$product_id."'";

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            set_transient(md5($sql), $result, 300);
        }
        
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

        $result = get_transient (md5($sql), 'vuefront');
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            wp_cache_add(md5($sql), $result, 'vuefront');
        }

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

        if (!empty($data['filter_manufacturer_id'])) {
            $implode[] = "'".$data['filter_manufacturer_id']."' IN (SELECT t.`slug` FROM wp_term_relationships rel
            LEFT JOIN wp_term_taxonomy tax ON tax.term_taxonomy_id = rel.term_taxonomy_id
            LEFT JOIN wp_terms t ON t.term_id = tax.term_id
            WHERE rel.`object_id` = p.ID AND tax.`taxonomy` = 'pwb-brand')";
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


        
        $results = get_transient(md5($sql));
        if($results === false) {
            $results = $wpdb->get_results( $sql );
            set_transient(md5($sql), $results, 300);
        }

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

        if (!empty($data['filter_manufacturer_id'])) {
            $implode[] = "'".$data['filter_manufacturer_id']."' IN (SELECT t.`slug` FROM wp_term_relationships rel
            LEFT JOIN wp_term_taxonomy tax ON tax.term_taxonomy_id = rel.term_taxonomy_id
            LEFT JOIN wp_terms t ON t.term_id = tax.term_id
            WHERE rel.`object_id` = p.ID AND tax.`taxonomy` = 'pwb-brand')";
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

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            set_transient(md5($sql), $result, 300);
        }

        return $result->total;
    }

    public function getCurrencySymbol($currency = '')
    {
        if (! $currency) {
            $currency = get_option( 'woocommerce_currency' );
        }

        $symbols = apply_filters('woocommerce_currency_symbols', array(
            'AED' => 'د.إ',
            'AFN' => '؋',
            'ALL' => 'L',
            'AMD' => 'AMD',
            'ANG' => 'ƒ',
            'AOA' => 'Kz',
            'ARS' => '$',
            'AUD' => '$',
            'AWG' => 'ƒ',
            'AZN' => 'AZN',
            'BAM' => 'KM',
            'BBD' => '$',
            'BDT' => '৳ ',
            'BGN' => 'лв.',
            'BHD' => '.د.ب',
            'BIF' => 'Fr',
            'BMD' => '$',
            'BND' => '$',
            'BOB' => 'Bs.',
            'BRL' => 'R$',
            'BSD' => '$',
            'BTC' => '฿',
            'BTN' => 'Nu.',
            'BWP' => 'P',
            'BYR' => 'Br',
            'BZD' => '$',
            'CAD' => '$',
            'CDF' => 'Fr',
            'CHF' => 'CHF',
            'CLP' => '$',
            'CNY' => '¥',
            'COP' => '$',
            'CRC' => '₡',
            'CUC' => '$',
            'CUP' => '$',
            'CVE' => '$',
            'CZK' => 'Kč',
            'DJF' => 'Fr',
            'DKK' => 'DKK',
            'DOP' => 'RD$',
            'DZD' => 'د.ج',
            'EGP' => 'EGP',
            'ERN' => 'Nfk',
            'ETB' => 'Br',
            'EUR' => '€',
            'FJD' => '$',
            'FKP' => '£',
            'GBP' => '£',
            'GEL' => 'ლ',
            'GGP' => '£',
            'GHS' => '₵',
            'GIP' => '£',
            'GMD' => 'D',
            'GNF' => 'Fr',
            'GTQ' => 'Q',
            'GYD' => '$',
            'HKD' => '$',
            'HNL' => 'L',
            'HRK' => 'Kn',
            'HTG' => 'G',
            'HUF' => 'Ft',
            'IDR' => 'Rp',
            'ILS' => '₪',
            'IMP' => '£',
            'INR' => '₹',
            'IQD' => 'ع.د',
            'IRR' => '﷼',
            'IRT' => 'تومان',
            'ISK' => 'kr.',
            'JEP' => '£',
            'JMD' => '$',
            'JOD' => 'د.ا',
            'JPY' => '¥',
            'KES' => 'KSh',
            'KGS' => 'сом',
            'KHR' => '៛',
            'KMF' => 'Fr',
            'KPW' => '₩',
            'KRW' => '₩',
            'KWD' => 'د.ك',
            'KYD' => '$',
            'KZT' => 'KZT',
            'LAK' => '₭',
            'LBP' => 'ل.ل',
            'LKR' => 'රු',
            'LRD' => '$',
            'LSL' => 'L',
            'LYD' => 'ل.د',
            'MAD' => 'د.م.',
            'MDL' => 'MDL',
            'MGA' => 'Ar',
            'MKD' => 'ден',
            'MMK' => 'Ks',
            'MNT' => '₮',
            'MOP' => 'P',
            'MRO' => 'UM',
            'MUR' => '₨',
            'MVR' => '.ރ',
            'MWK' => 'MK',
            'MXN' => '$',
            'MYR' => 'RM',
            'MZN' => 'MT',
            'NAD' => '$',
            'NGN' => '₦',
            'NIO' => 'C$',
            'NOK' => 'kr',
            'NPR' => '₨',
            'NZD' => '$',
            'OMR' => 'ر.ع.',
            'PAB' => 'B/.',
            'PEN' => 'S/.',
            'PGK' => 'K',
            'PHP' => '₱',
            'PKR' => '₨',
            'PLN' => 'zł',
            'PRB' => 'р.',
            'PYG' => '₲',
            'QAR' => 'ر.ق',
            'RMB' => '¥',
            'RON' => 'lei',
            'RSD' => 'дин.',
            'RUB' => '₽',
            'RWF' => 'Fr',
            'SAR' => 'ر.س',
            'SBD' => '$',
            'SCR' => '₨',
            'SDG' => 'ج.س.',
            'SEK' => 'kr',
            'SGD' => '$',
            'SHP' => '£',
            'SLL' => 'Le',
            'SOS' => 'Sh',
            'SRD' => '$',
            'SSP' => '£',
            'STD' => 'Db',
            'SYP' => 'ل.س',
            'SZL' => 'L',
            'THB' => '฿',
            'TJS' => 'ЅМ',
            'TMT' => 'm',
            'TND' => 'د.ت',
            'TOP' => 'T$',
            'TRY' => '₺',
            'TTD' => '$',
            'TWD' => 'NT$',
            'TZS' => 'Sh',
            'UAH' => '₴',
            'UGX' => 'UGX',
            'USD' => '$',
            'UYU' => '$',
            'UZS' => 'UZS',
            'VEF' => 'Bs F',
            'VND' => '₫',
            'VUV' => 'Vt',
            'WST' => 'T',
            'XAF' => 'Fr',
            'XCD' => '$',
            'XOF' => 'Fr',
            'XPF' => 'Fr',
            'YER' => '﷼',
            'ZAR' => 'R',
            'ZMW' => 'ZK',
        ));

        $currency_symbol = isset($symbols[ $currency ]) ? $symbols[ $currency ] : '';

        return apply_filters('woocommerce_currency_symbol', $currency_symbol, $currency);
    }
}
