<?php

class VFA_ResolverStoreCheckout extends VFA_Resolver
{
    public function link()
    {
        return array(
            'link' => WC()->cart->get_checkout_url()
        );
    }

    public function paymentMethods()
    {
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

        foreach ($response['payments'] as $key => $value) {
            if ($value['status']) {
                $methods[] = array(
                    'id' => $value['codename'],
                    'codename' => $value['codename'],
                    "name" => $value['name']
                );
            }
        }

        return $methods;
    }

    public function shippingMethods()
    {
        $methods = array();

        $WC_Shipping = new WC_Shipping();
        $var = $WC_Shipping->get_shipping_methods();

        foreach ($var as $key => $value) {
            $methods[] = array(
                'id' => $value->id,
                'codename' => $value->id,
                "name" => $value->method_title
            );
        }

        return $methods;
    }


    public function paymentAddress()
    {
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
            'name' => 'state',
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

    public function shippingAddress()
    {
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
            'name' => 'state',
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

    public function createOrder($args)
    {
        $shipping_address = array();


        foreach ($this->shippingAddress() as $value) {
            $shipping_address[$value['name']] = '';
        }

        setcookie('shipping_address', json_encode($shipping_address), 0, "/");

        $payment_address = array(
            'custom_field' => array()
        );

        $paymentAddress = $this->paymentAddress();
        foreach ($paymentAddress['fields'] as $value) {
            $payment_address[$value['name']] = '';
        
        }

        setcookie('payment_address', json_encode($payment_address), 0, "/");

        setcookie('payment_method', null, 0, "/");
        setcookie('shipping_method', null, 0, "/");

        return array('success'=> 'success');
    }

    public function updateOrder($args)
    {
        $payment_address = json_decode(stripslashes($_COOKIE['payment_address']), true);
        $shipping_address = json_decode(stripslashes($_COOKIE['shipping_address']), true);

        foreach ($args['paymentAddress'] as $value) {
            if (strpos($value['name'], "vfCustomField-") !== false) {
                if ($value['value']) {
                    $field_name = str_replace("vfCustomField-", "", $value['name']);
                    $field_name = explode('-', $field_name);
                    if (!isset($payment_address['custom_field'][$field_name[0]])) {
                        $payment_address['custom_field'][$field_name[0]] = array();
                    }
                    $payment_address['custom_field'][$field_name[0]][$field_name[1]] = $value['value'];
                }
            } else {
                if ($value['value']) {
                    $payment_address[$value['name']] = $value['value'];
                }
            }
        }

        setcookie('payment_address', json_encode($payment_address), 0, "/");
        $_COOKIE['payment_address'] = json_encode($payment_address);

        foreach ($args['shippingAddress'] as $value) {
            if (strpos($value['name'], "vfCustomField-") !== false) {
                if ($value['value']) {
                    $field_name = str_replace("vfCustomField-", "", $value['name']);
                    $field_name = explode('-', $field_name);
                    if (!isset($shipping_address['custom_field'][$field_name[0]])) {
                        $shipping_address['custom_field'][$field_name[0]] = array();
                    }
                    $shipping_address['custom_field'][$field_name[0]][$field_name[1]] = $value['value'];
                }
            } else {
                if ($value['value']) {
                    $shipping_address[$value['name']] = $value['value'];
                }
            }
        }

        setcookie('shipping_address', json_encode($shipping_address), 0, "/");
        $_COOKIE['shipping_address'] = json_encode($shipping_address);

        setcookie('shipping_method', json_encode($args['shippingMethod']), 0, "/");
        $_COOKIE['shipping_method'] = json_encode($args['shippingMethod']);

        setcookie('payment_method', json_encode($args['paymentMethod']), 0, "/");
        $_COOKIE['payment_method'] = json_encode($args['paymentMethod']);


		WC()->customer->set_props(
			array(
                'billing_country'   => $payment_address['country'],
                'shipping_country' => $shipping_address['country'],
				'billing_state'     => $payment_address['state'],
				'shipping_state'     => $shipping_address['state'],
				'billing_postcode'  => $payment_address['postcode'],
				'shipping_postcode'  => $shipping_address['postcode'],
				'billing_city'      => $payment_address['city'],
				'shipping_city'      => $shipping_address['city'],
				'billing_address_1' => $payment_address['address1'],
				'shipping_address_1' => $shipping_address['address1'],
				'billing_address_2' => $payment_address['address2'],
				'shipping_address_2' => $shipping_address['address2']
			)
        );

        WC()->cart->calculate_shipping();
		WC()->cart->calculate_totals();

        return array(
            'paymentMethods' => $this->load->resolver('store/checkout/paymentMethods'),
            'shippingMethods' => $this->load->resolver('store/checkout/shippingMethods'),
            'totals' => $this->load->resolver('store/checkout/totals'),
        );
    }


    public function confirmOrder($args)
    {
        $this->load->model('store/checkout');

        $paymentAddress = json_decode(stripslashes($_COOKIE['payment_address']), true);
        $shippingAddress = json_decode(stripslashes($_COOKIE['shipping_address']), true);
        $paymentMethod = json_decode(stripslashes($_COOKIE['payment_method']), true);
        $shippingMethod = json_decode(stripslashes($_COOKIE['shipping_method']), true);

        $response = $this->model_store_checkout->requestCheckout(
            'query($codename: String){
                payment(codename: $pCodename) {
                    codename
                    name
                }
            }',
            array(
                'codename' => $paymentMethod
            )
        );

        $paymentMethod = $response['payment'];

        global $woocommerce;

        // Now we create the order
        $order = wc_create_order();
        $WC_Shipping = new WC_Shipping();
        $var = $WC_Shipping->get_shipping_methods();

        $shipping_method = false;

        foreach ($var as $key => $value) {
            if($shippingMethod === $value->id) {
                $shipping_method = $value;
            }
        }

        $shipping_rate = new WC_Shipping_Rate($shipping_method->get_rate_id(), $shipping_method->get_method_title());

        $order->add_shipping($shipping_rate);

        foreach ($woocommerce->cart->get_cart() as $values) {
            $order->add_product(
                $values['data'],
                $values['quantity'],
                array(
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

        $customerId = 0;
        $customerEmail = '';

        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $customerId = $user->ID;
            $customerEmail = $user->user_email;
        }

        $order->set_address(array(
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
        ), 'billing');

        $customerEmail=$paymentAddress['email'];

        $order->set_address(array(
            'first_name' => $shippingAddress['firstName'],
            'last_name'  => $shippingAddress['lastName'],
            'company'    => $shippingAddress['company'],
            'address_1'  => $shippingAddress['address1'],
            'address_2'  => $shippingAddress['address2'],
            'city'       => $shippingAddress['city'],
            'postcode'   => $shippingAddress['postcode'],
            'country'    => $shippingAddress['country'],
        ), 'shipping');

        $order->calculate_totals();

        $order->set_payment_method($paymentMethod['name']);
        
        $orderId = $order->get_order_number();
        if ($args['withPayment']) {
            $response = $this->model_store_checkout->requestCheckout(
                'mutation($paymentMethod: String, $total: Float, $callback: String, $customerId: String, $customerEmail: String) {
                    createOrder(paymentMethod: $paymentMethod, total: $total, callback: $callback, customerId: $customerId, customerEmail: $customerEmail) {
                        url
                    }
                }',
                array(
                    'paymentMethod' => $paymentMethod['codename'],
                    'total' => floatval($order->get_total()),
                    'customerEmail' => $customerEmail,
                    'customerId' => $customerId,
                    'callback' => urldecode(add_query_arg(
                        array(
                            'order_id' => $orderId
                        ),
                        get_rest_url(null, '/vuefront/v1/callback')
                    ))
                )
            );
        } else {
            $response = array(
                'createOrder' => array(
                    'url' => ''
                )
            );
        }

        return array(
            'url' => $response['createOrder']['url'],
            'callback' => urldecode(add_query_arg(
                array(
                    'order_id' => $orderId
                ),
                get_rest_url(null, '/vuefront/v1/callback')
            )),
            'order' => array(
                'id' => $orderId
            )
        );
    }

    public function callback()
    {
        $order_id = $_GET['order_id'];
        $rawInput = file_get_contents('php://input');

        $input = json_decode($rawInput, true);
        if ($input['status'] == 'COMPLETE') {
            $order = wc_get_order($order_id);
            $order->update_status('completed');
        }
    }

    public function totals()
    {
        $this->load->model('store/product');
        $result = array();

        $result[] = array(
            'title' => translate( 'Subtotal', 'woocommerce' ),
            'text' =>$this->currency->format(WC()->cart->get_subtotal())
        );

        foreach ( WC()->cart->get_coupons() as $code => $coupon ) {
            $result[] = array(
                'title' => wc_cart_totals_coupon_label($coupon, false),
                'text' => '-' .$this->currency->format(WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax ))
            );
        }

        foreach ( WC()->cart->get_fees() as $fee ) {
            $result[] = array(
                'title' => $fee->name,
                'text' =>$this->currency->convert(wc_cart_totals_fee_html($fee))
            );
        }

        if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()) {
            if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                foreach ( WC()->cart->get_tax_totals() as $code => $tax ) {
                    $result[] = array(
                        'title' => $tax->label,
                        'text' => $this->currency->convert($tax->formatted_amount)
                    );
                }
            } else {
                $result[] = array(
                    'title' => WC()->countries->tax_or_vat(),
                    'text' => $this->currency->format(WC()->cart->get_total_tax())
                );
            }
        }

        $result[] = array(
            'title' => translate( 'Total', 'woocommerce' ),
            'text' => $this->currency->convert(WC()->cart->get_total())
        );

        return $result;
    }
}
