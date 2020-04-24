<?php


class VFA_Currency
{
    public function format($price) {
        return html_entity_decode(strip_tags(wc_price($price)),ENT_QUOTES ,'UTF-8');
    }

    public function convert($price) {
        return html_entity_decode(strip_tags($price),ENT_QUOTES ,'UTF-8');
    }
}