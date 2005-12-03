<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute pages for Google Base
*/

function gb_validate_pages($value)
{
    return empty($value) ? true : (is_integer($value) && $value > 0);
}

function gb_suggest_pages($value)
{
    gb_cast_pages($value);
    return $value;
}

function gb_cast_pages(&$value)
{
    $value = (int)floor(intval($value));
    $value = $value == 0 ? '' : $value;
}

?>
