<?php

class ResolverStoreCheckout extends Resolver
{
    public function link() {
        return array(
            'link' => WC()->cart->get_checkout_url()
        );
    }
}