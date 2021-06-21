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

    public function getOptionValues($name) {
        global $wpdb;

        $sql = "SELECT * FROM `".$wpdb->prefix."term_taxonomy` tt WHERE tt.`taxonomy` =  '".$name."'";

        $result = get_transient(md5($sql));

        if($result === false) {
            $result = $wpdb->get_results( $sql );
            set_transient(md5($sql), $result, 300);
        }
        return $result;
    }

    public function getOptions($data) {
        global $wpdb;

        $sql = "SELECT wat.* FROM `".$wpdb->prefix."woocommerce_attribute_taxonomies` wat";

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_results( $sql );
            set_transient(md5($sql), $result, 300);
        }

        return $result;
    }

    public function getOption($id) {
        global $wpdb;

        $sql = "SELECT wat.* FROM `".$wpdb->prefix."woocommerce_attribute_taxonomies` wat WHERE wat.`attribute_name` = '".str_replace('pa_', '', $id)."'";

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            set_transient(md5($sql), $result, 300);
        }

        return $result;
    }

    public function getTotalOptions($data) {
        global $wpdb;

        $sql = "SELECT count(*) as total FROM `".$wpdb->prefix."woocommerce_attribute_taxonomies` wat";

        $result = get_transient(md5($sql));
        if($result === false) {
            $result = $wpdb->get_row( $sql );
            set_transient(md5($sql), $result, 300);
        }

        return $result->total;
    }
}