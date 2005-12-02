<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute title for Google Base
*/

function gb_validate_title($value)
{
    return strlen($value) <= 80;
}

function gb_suggest_title($value)
{
    return !empty($value) ? substr($value,0,77).'...' : $value;
}

function gb_cast_title(&$value)
{
}

?>
