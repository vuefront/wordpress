<?php

class VFA_ResolverStoreOption extends VFA_Resolver
{
    public function getList($args)
    {
        $this->load->model('store/option');

        if (in_array($args['sort'], array('sort_order', ))) {
            $args['sort'] = 'o.' . $args['sort'];
        } elseif (in_array($args['sort'], array('name'))) {
            $args['sort'] = 'od.' . $args['sort'];
        }

        $options = array();

        $filter_data = array(
            'sort' => $args['sort'],
            'order' => $args['order']
        );

        if($args['size'] != '-1') {
            $filter_data['start'] = ($args['page'] - 1) * $args['size'];
            $filter_data['limit'] = $args['size'];
        }

        if (!empty($args['search'])) {
            $filter_data['filter_name'] = $args['search'];
        }

        $option_total = $this->model_store_option->getTotalOptions($filter_data);

        $results = $this->model_store_option->getOptions($filter_data);

        if ($args['size'] == -1 && $option_total != 0) {
            $args['size'] = $option_total;
        } else if($args['size'] == -1 && $option_total == 0) {
            $args['size'] = 1;
        }
        foreach ($results as $result) {
            $options[] = $this->get(array('id' => "pa_".$result->attribute_name));
        }

        return array(
            'content' => $options,
            'first' => $args['page'] === 1,
            'last' => $args['page'] === ceil($option_total / $args['size']),
            'number' => (int)$args['page'],
            'numberOfElements' => count($options),
            'size' => (int)$args['size'],
            'totalPages' => (int)ceil($option_total / $args['size']),
            'totalElements' => (int)$option_total,
        );
    }

    public function get($args)
    {
        $this->load->model('store/option');
        $option_info = $this->model_store_option->getOption($args['id']);

        if (!$option_info) {
            return array();
        }


        return array(
            'id' => "pa_" . $option_info->attribute_name,
            'name' => html_entity_decode($option_info->attribute_label, ENT_QUOTES, 'UTF-8'),
            'type' => $option_info->attribute_type,
            'sort_order' => 0,
            'values' => function($root, $args) {
                return $this->getValues(array(
                    'parent' => $root,
                    'args' => $args
                ));
            }
        );
    }

    public function getValues($data) {
        $this->load->model('store/option');
        $this->load->model('store/product');
        $results = array();

        $option_values = $this->model_store_product->getOptionValues($data['parent']['id']);

        foreach ($option_values as $key => $value) {
            $results[] = array(
                'id' => $value->slug,
                'name' => $value->name
            );
        }

        return $results;
    }
}