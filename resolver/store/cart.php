<?php

class VFA_ResolverStoreCart extends VFA_Resolver
{
	public function add($args) {
        $this->load->model('store/product');
        $this->load->model('store/cart');

        $product = $this->model_store_product->getProduct($args['id']);

        if ($product->type == 'variable') {
            $options = array();
            foreach ($args['options'] as $option) {
                $options[ $option['id'] ] = $option['value'];
            }
            $variation_id = $this->find_matching_product_variation_id($args['id'], $options);
            if(!$variation_id) {
                throw new \Exception('Please select an option.');
            }
            WC()->cart->add_to_cart($args['id'], $args['quantity'], $variation_id);
        } else {
            WC()->cart->add_to_cart($args['id'], $args['quantity']);
        }

        $this->load->model('common/vuefront');
        $this->model_common_vuefront->pushEvent('update_cart', array(
            'cart' => $this->model_store_cart->prepareCart(),
            'customer_id' => '',
            'guest' => false
        ));

        return $this->get($args);
    }
    public function update($args) {
        WC()->cart->set_quantity($args['key'], $args['quantity']);

        $this->load->model('common/vuefront');
        $this->model_common_vuefront->pushEvent('update_cart', array(
            'cart' => $this->model_store_cart->prepareCart(),
            'customer_id' => '',
            'guest' => false
        ));

        return $this->get($args);
    }
    public function remove($args) {
        WC()->cart->remove_cart_item($args['key']);

        $this->load->model('common/vuefront');
        $this->model_common_vuefront->pushEvent('update_cart', array(
            'cart' => $this->model_store_cart->prepareCart(),
            'customer_id' => '',
            'guest' => false
        ));

        return $this->get($args);
    }
    public function get($args) {
        $cart = array();
        $this->load->model('store/product');
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
                'option'   => array(),
                'total'    => $this->currency->format($product['line_total'])
            );
        }

        $total = !empty($cart['products']) ? WC()->cart->total : 0;
        
        $cart['total'] = $this->currency->format($total);

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