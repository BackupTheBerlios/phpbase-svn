<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute bathrooms for Google Base
*/

function gb_validate_bathrooms($value)
{
    return empty($value) ? true : (is_integer($value) && $value > 0);
}

function gb_suggest_bathrooms($value)
{
    gb_cast_bathrooms($value);
    return intval($value);
}

function gb_cast_bathrooms(&$value)
{
    $value = (int)floor(intval($value));
    $value = $value == 0 ? '' : $value;    
}

?>
