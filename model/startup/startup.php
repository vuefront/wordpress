<?php

class VF_ModelStartupStartup extends VF_Model
{
    public function getVF_Resolvers()
    {
        $rawMapping = file_get_contents(VF_DIR_PLUGIN.'mapping.json');
        $mapping = json_decode($rawMapping, true);
        $result = array();
        foreach ($mapping as $key => $value) {
            $that = $this;
            $result[$key] = function ($root, $args, $context) use ($value, $that) {
                try {
                    return $that->load->resolver($value, $args);
                } catch (\Exception $e) {
                    $message = preg_replace('/(\s+)?\<a .*\>.*\<\/a\>(\s)?/', '',$e->getMessage());
                    throw new VF_MySafeException($message);
                }
            };
        }

        return $result;
    }
}
