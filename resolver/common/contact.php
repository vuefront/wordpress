<?php

class VFA_ResolverCommonContact extends VFA_Resolver
{
    private $codename = "vuefront";

    public function get()
    {
        $store_raw_country = get_option('woocommerce_default_country');
        $split_country = explode(":", $store_raw_country);

        $address = array(
            'address_1' => get_option('woocommerce_store_address'),
            'address_2' => get_option('woocommerce_store_address_2'),
            'city' => get_option('woocommerce_store_city'),
            'postcode' => get_option('woocommerce_store_postcode'),
            'country' => $split_country[0],
            'state' => $split_country[1]
        );

        $WC_Countries = new WC_Countries();

        return array(
            'store' => get_bloginfo('name'),
            'email' => get_bloginfo('admin_email'),
            'address' => $WC_Countries->get_formatted_address($address, ', '),
            'geocode' => '',
            'locations' => array(),
            'telephone' => '',
            'fax' => '',
            'open' => '',
            'comment' => get_bloginfo('description')
        );
    }

    public function send($args)
    {
        wp_mail(get_bloginfo('admin_email'), 'Enquiry '.$args['name'],$args['message'], array(
            'reply-to' => $args['email']
        ));

        return array(
            "status" => true
        );
    }
}