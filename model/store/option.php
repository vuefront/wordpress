<?php

class ModelStoreOption extends Model
{
    public function getOptionLabel($name) {
        global $wpdb;

        $result = $wpdb->get_row("SELECT wat.`attribute_label` AS label FROM `wp_woocommerce_attribute_taxonomies` wat WHERE wat.`attribute_name` = '".str_replace('pa_', '', $name)."'");

        return $result->label;
    }
}