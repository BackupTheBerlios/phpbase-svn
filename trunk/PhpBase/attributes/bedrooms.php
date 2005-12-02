<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute bedrooms for Google Base
*/

function gb_validate_bedrooms($value)
{
    return empty($value) ? true : (is_integer($value) && $value > 0);
}

function gb_suggest_bedrooms($value)
{
    gb_cast_bedrooms($value);
    return $value;
}

function gb_cast_bedrooms(&$value)
{
    $value = (int)floor(intval($value));
    $value = $value == 0 ? '' : $value;
}

?>
