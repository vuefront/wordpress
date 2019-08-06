<?php

class VFA_ResolverStoreCheckout extends VFA_Resolver
{
    public function link() {
        return array(
            'link' => WC()->cart->get_checkout_url()
        );
    }
}