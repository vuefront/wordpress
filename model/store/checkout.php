<?php

class VFA_ModelStoreCheckout extends VFA_Model {
    public function getJwt($codename) {
        $setting = get_option('vuefront-apps');

        $result = false;

        foreach ($setting as $key => $value) {
            if($value['codename'] == $codename) {
                $result = $value['jwt'];
            }
        }

        return $result;
    }
    public function requestCheckout($query, $variables) {
        $jwt = $this->getJwt('vuefront-checkout-app');
        
        $ch = curl_init();  

        $requestData = array(
            'operationName' => null,
            'variables' => $variables,
            'query' => $query
        );

        $headr = array();
        
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: '.$jwt;

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($requestData, JSON_FORCE_OBJECT) ); 
        // curl_setopt($ch, CURLOPT_URL, 'http://localhost:3005/graphql'); 
        curl_setopt($ch, CURLOPT_URL, 'https://api.checkout.vuefront.com/graphql'); 

        $result = curl_exec($ch); 

        $result = json_decode($result, true);

        return $result['data'];
    }
}