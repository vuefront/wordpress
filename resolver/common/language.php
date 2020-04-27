<?php

class VFA_ResolverCommonLanguage extends VFA_Resolver
{
    private $codename = "vuefront";

    public function get()
    {
        $languages = array();

        $languages[] = array(
            'name'   => 'English',
            'code'   => 'en-gb',
            'image'  => '',
            'active' => true
        );

        return $languages;
    }

    public function edit($args)
    {
        return $this->get();
    }
}
