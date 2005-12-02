<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute link for Google Base
*/

function gb_validate_link($value)
{
    return (strtolower(substr($value,0,7)) == 'http://' || strtolower(substr($value,0,8)) == 'https://');
}

function gb_suggest_link($value)
{
    return !empty($value) && $value{0} != '/' ? 'http://'.$value : (gb_validate_link($value) ? $value : '');
}

function gb_cast_link(&$value)
{
}

?>
