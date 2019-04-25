<?php

class ModelStartupStartup extends Model
{
    public function getResolvers() {
        $rawMapping = file_get_contents(DIR_PLUGIN.'mapping.json');
        $mapping = json_decode( $rawMapping, true );
        $result = array();
        foreach ($mapping as $key => $value) {
            $that = $this;
            $result[$key] = function($root, $args, $context) use ($value, $that) {
                return $that->load->resolver($value, $args);
            };
        }

        return $result;
    }
}