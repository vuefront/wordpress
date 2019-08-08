<?php

class VFA_ResolverStoreCurrency extends VFA_Resolver
{
    private $codename = "vuefront";

    public function get()
    {
        $currencies = array();
        $this->load->model('store/product');
        $currencies[] = array(
            'title'        => get_option( 'woocommerce_currency' ),
            'name'         => get_option( 'woocommerce_currency' ),
            'code'         => get_option( 'woocommerce_currency' ),
            'symbol_left'  => $this->model_store_product->getCurrencySymbol(),
            'symbol_right' => '',
            'active' => true
        );

        return $currencies;
    }

    public function edit($args)
    {
        return $this->get();
    }
}
