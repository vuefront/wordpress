<?php

class VFA_ModelStoreOption extends VFA_Model
{
    public function getOptionLabel($name) {
        global $wpdb;

        $result = $wpdb->get_row("SELECT wat.`attribute_label` AS label FROM `".$wpdb->prefix."woocommerce_attribute_taxonomies` wat WHERE wat.`attribute_name` = '".str_replace('pa_', '', $name)."'");

        return $result->label;
    }
}