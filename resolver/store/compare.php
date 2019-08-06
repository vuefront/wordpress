<?php 

class VFA_ResolverStoreCompare extends VFA_Resolver {
    public function add($args) {
        $this->load->model('store/compare');

        $this->model_store_compare->addCompare($args['id']);

        return $this->get();
    }
    public function remove($args) {
        $this->load->model('store/compare');
        $this->model_store_compare->deleteCompare($args['id']);

        return $this->get();
    }
    public function get($args = array()) {
        $this->load->model('store/compare');
        $compare = array();
        $results = $this->model_store_compare->getCompare();

        foreach ($results as $product_id) {
            $compare[] = $this->load->resolver('store/product/get', array('id' => $product_id));
        }

        return $compare;
    }
}