<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute price_type for Google Base
*/

function gb_validate_price_type($value)
{
    return in_array($value, array('negotiable','starting'));
}

function gb_suggest_price_type($value)
{
    return strtolower($value) == 'negotiable' ? 'negotiable' : 'starting';
}

function gb_cast_price_type(&$value)
{
}

?>
