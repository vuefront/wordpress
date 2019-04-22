<?php

class ResolverStartupSession extends Resolver
{
    public function index() {
        if(!session_id()) {
            session_start();
        }
    }
}