<?php

class VFA_ModelStoreCart extends VFA_Model {
    public function prepareCart() {
        $cart = array();
        foreach (WC()->cart->get_cart() as $product) {
            $this->load->model('store/product');
            $cart['products'] = array();
            foreach (WC()->cart->get_cart() as $product) {
                if ($product['variation_id'] !== 0) {
                    $product_id = $product['variation_id'];
                } else {
                    $product_id = $product['product_id'];
                }
                $option_data = array();

                foreach ($product['variation'] as $key => $value) {
                    $option_data[] = array(
                        'option_id' => str_replace('attribute_', '', $key),
                        'option_value_id' => $value
                    );
                }

                $cart['products'][] = array(
                    'key'      => $product['key'],
                    'product'  => array(
                        'product_id' => $product_id,
                        'price' => '1'
                    ),
                    'quantity' => $product['quantity'],
                    'option'   => $option_data,
                    'total'    => $this->currency->format($product['line_total'])
                );
            }
        }
    }
}