<?php

class VFA_ModelStoreOption extends VFA_Model
{
    public function getOptionLabel($name) {
        global $wpdb;

        $sql = "SELECT wat.`attribute_label` AS label FROM `".$wpdb->prefix."woocommerce_attribute_taxonomies` wat WHERE wat.`attribute_name` = '".str_replace('pa_', '', $name)."'";

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            set_transient(md5($sql), $result, 300);
        }

        return $result->label;
    }
}