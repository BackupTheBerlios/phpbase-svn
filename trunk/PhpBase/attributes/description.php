<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute description for Google Base
*/

function gb_validate_description($value)
{
    return strlen($value) <= 65536;
}

function gb_suggest_description($value)
{
    return !empty($value) ? substr($value,0,65533).'...' : $value;
}

function gb_cast_description(&$value)
{
    $value = trim($value,' .,');
}

?>
