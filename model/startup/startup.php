<?php

class ModelStartupStartup extends Model
{
    public function getQueries() {
        $result = array();

        $files = glob(DIR_PLUGIN.'type/**/*.php', GLOB_BRACE);
    
        foreach ($files as $filepath) {
            $route = str_replace(DIR_PLUGIN.'type/', '', $filepath);
            $route = str_replace('.php', '', $route);
            $output = $this->load->type($route.'/getQuery');

            if($output) {
                $result = array_merge( $result, $output );
            }
        }
    
        return $result;
    }

    public function getMutations() {
        $result = array();

        $files = glob(DIR_PLUGIN.'type/**/*.php', GLOB_BRACE);
    
        foreach ($files as $filepath) {
            $route = str_replace(DIR_PLUGIN.'type/', '', $filepath);
            $route = str_replace('.php', '', $route);
            $output = $this->load->type($route.'/getMutations');
    
            if($output) {
                $result = array_merge( $result, $output );
            }
        }
    
        return $result;
    }
}