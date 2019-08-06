<?php

class VF_ResolverStoreCheckout extends VF_Resolver
{
    public function link() {
        return array(
            'link' => WC()->cart->get_checkout_url()
        );
    }
}