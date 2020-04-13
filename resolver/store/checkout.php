<?php

class VFA_ResolverStoreCheckout extends VFA_Resolver
{
    public function link() {
        return array(
            'link' => WC()->cart->get_checkout_url()
        );
    }

    public function paymentMethods() {
        $this->load->model('store/checkout');

        $response = $this->model_store_checkout->requestCheckout(
            '{
                payments {
                    setting
                    codename
                    status
                    name
              }
            }',
            array()
        );

        $methods = array();

        foreach($response['payments'] as $key => $value) {
            if($value['status']) {
                $methods[] = array(
                    'id' => $value['codename'],
                    'codename' => $value['codename'],
                    "name" => $value['name']
                );    
            }
        }

        return $methods;
    }

    public function shippingMethods() {
        $this->load->model('store/checkout');

        $response = $this->model_store_checkout->requestCheckout(
            '{
                shippings {
                    setting
                    codename
                    status
                    name
              }
            }',
            array()
        );

        $methods = array();

        foreach($response['shippings'] as $key => $value) {
            if($value['status']) {
                $methods[] = array(
                    'id' => $value['codename'],
                    'codename' => $value['codename'],
                    "name" => $value['name']
                );    
            }
        }

        return $methods;
    }


    public function paymentAddress() {
        $fields = array();

        $fields[] = array(
            'type' => 'text',
            'name' => 'firstName',
            'required' => true
        );
        $fields[] = array(
            'type' => 'text',
            'name' => 'lastName',
            'required' => true
        );

        $fields[] = array(
            'type' => 'text',
            'name' => 'company',
            'required' => false
        );

        $fields[] = array(
            'type' => 'country',
            'name' => 'country',
            'required' => true
        );

        $fields[] = array(
            'type' => 'text',
            'name' => 'address1',
            'required' => true
        );
        $fields[] = array(
            'type' => 'text',
            'name' => 'address2',
            'required' => false
        );
        $fields[] = array(
            'type' => 'text',
            'name' => 'city',
            'required' => true
        );

        $fields[] = array(
            'type' => 'text',
            'name' => 'postcode',
            'required' => true
        );

        $fields[] = array(
            'type' => 'text',
            'name' => 'phone',
            'required' => true
        );

        $fields[] = array(
            'type' => 'text',
            'name' => 'email',
            'required' => true
        );

        $agree = null;

        return array(
            'fields' => $fields,
            'agree' => $agree
        );
    }

    public function shippingAddress() {
        $fields = array();

        $fields[] = array(
            'type' => 'text',
            'name' => 'firstName',
            'required' => true
        );
        $fields[] = array(
            'type' => 'text',
            'name' => 'lastName',
            'required' => true
        );

        $fields[] = array(
            'type' => 'text',
            'name' => 'company',
            'required' => false
        );

        $fields[] = array(
            'type' => 'country',
            'name' => 'country',
            'required' => true
        );

        $fields[] = array(
            'type' => 'text',
            'name' => 'address1',
            'required' => true
        );
        $fields[] = array(
            'type' => 'text',
            'name' => 'address2',
            'required' => false
        );
        $fields[] = array(
            'type' => 'text',
            'name' => 'city',
            'required' => true
        );

        $fields[] = array(
            'type' => 'text',
            'name' => 'postcode',
            'required' => true
        );

        $fields[] = array(
            'type' => 'text',
            'name' => 'phone',
            'required' => true
        );

        $fields[] = array(
            'type' => 'text',
            'name' => 'email',
            'required' => true
        );

        return $fields;
    }


    public function createOrder($args) {
        $this->load->model('store/checkout');

        $paymentAddress = array();

        foreach ($args['paymentAddress'] as $value) {
            $paymentAddress[$value['name']] = $value['value'];
        }

        $shippingAddress = array();

        foreach ($args['shippingAddress'] as $value) {
            $shippingAddress[$value['name']] = $value['value'];
        }

        $response = $this->model_store_checkout->requestCheckout(
            'query($pCodename: String, $sCodename: String){
                payment(codename: $pCodename) {
                    codename
                    name
                }
                shipping(codename: $sCodename) {
                    codename
                    name
                }
            }',
            array(
                'pCodename' => $args['paymentMethod'],
                'sCodename' => $args['shippingMethod']
            )
        );

        $shippingMethod = $response['shipping'];
        $paymentMethod = $response['payment'];

        $order_data = array();

        global $woocommerce;

        // Now we create the order
        $order = wc_create_order();
        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
            $item_id = $order->add_product(
                    $values['data'], $values['quantity'], array(
                'variation' => $values['variation'],
                'totals' => array(
                    'subtotal' => $values['line_subtotal'],
                    'subtotal_tax' => $values['line_subtotal_tax'],
                    'total' => $values['line_total'],
                    'tax' => $values['line_tax'],
                    'tax_data' => $values['line_tax_data'] // Since 2.2
                )
                    )
            );
        }

        $order->set_address( array(
            'first_name' => $paymentAddress['firstName'],
            'last_name'  => $paymentAddress['lastName'],
            'company'    => $paymentAddress['company'],
            'email'      => $paymentAddress['email'],
            'phone'      => $paymentAddress['phone'],
            'address_1'  => $paymentAddress['address1'],
            'address_2'  => $paymentAddress['address2'],
            'city'       => $paymentAddress['city'],
            'postcode'   => $paymentAddress['postcode'],
            'country'    => $paymentAddress['country'],
        ), 'billing' );

        $order->set_address( array(
            'first_name' => $shippingAddress['firstName'],
            'last_name'  => $shippingAddress['lastName'],
            'company'    => $shippingAddress['company'],
            'address_1'  => $shippingAddress['address1'],
            'address_2'  => $shippingAddress['address2'],
            'city'       => $shippingAddress['city'],
            'postcode'   => $shippingAddress['postcode'],
            'country'    => $shippingAddress['country'],
        ), 'shipping' );

        $order->calculate_totals();

        $order->set_payment_method($paymentMethod['name']);
        

        $orderId = $order->get_order_number();

        $response = $this->model_store_checkout->requestCheckout(
            'mutation($paymentMethod: String, $shippingMethod: String, $total: Float, $callback: String) {
                createOrder(paymentMethod: $paymentMethod, shippingMethod: $shippingMethod, total: $total, callback: $callback) {
                    url
                }
            }',
            array(
                'paymentMethod' => $paymentMethod['codename'],
                'shippingMethod' => $shippingMethod['codename'],
                'total' => floatval($order->get_total()),
                'callback' => urldecode(add_query_arg(
                    array(
                        'order_id' => $orderId 
                    ), 
                    get_rest_url( null, '/vuefront/v1/callback')
                ))
            )
        );

        return array(
            'url' => $response['createOrder']['url']
        );
    }

    public function callback() {
        $order_id = $_GET['order_id'];
        $rawInput = file_get_contents('php://input');

        $input = json_decode($rawInput, true);
        if($input['status'] == 'COMPLETE') {
            $order = wc_get_order( $order_id );
            $order->update_status( 'completed' );
        }
    }
}