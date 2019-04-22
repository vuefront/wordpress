<?php

class ResolverStoreCart extends Resolver
{
    public function add($args) {
        $product = wc_get_product($args['id']);

        if ($product->is_type('variable')) {
            $options = array();
            foreach ($args['options'] as $option) {
                $options[ $option['id'] ] = $option['value'];
            }
            $variation_id = $this->find_matching_product_variation_id($args['id'], $options);
            WC()->cart->add_to_cart($args['id'], $args['quantity'], $variation_id);
        } else {
            WC()->cart->add_to_cart($args['id'], $args['quantity']);
        }

        return $this->get($args);
    }
    public function update($args) {
        WC()->cart->set_quantity($args['key'], $args['quantity']);

        return $this->get($args);
    }
    public function remove($args) {
        WC()->cart->remove_cart_item($args['key']);

        return $this->get($args);
    }
    public function get($args) {
        $cart = array();

        $cart['products'] = array();
        foreach (WC()->cart->get_cart() as $product) {
            if ($product['variation_id'] !== 0) {
                $product_id = $product['variation_id'];
            } else {
                $product_id = $product['product_id'];
            }
            $cart['products'][] = array(
                'key'      => $product['key'],
                'product'  => $this->load->resolver('store/product/get', array( 'id' => $product_id )),
                'quantity' => $product['quantity'],
                'total'    => $product['line_total'] . ' ' . $this->model_store_product->getCurrencySymbol()
            );
        }

        return $cart;
    }

    public function find_matching_product_variation_id($product_id, $attributes)
    {
        return ( new \WC_Product_Data_Store_CPT() )->find_matching_product_variation(
            new \WC_Product($product_id),
            $attributes
        );
    }
}