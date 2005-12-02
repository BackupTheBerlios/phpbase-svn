<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute age for Google Base
*/

function gb_validate_age($value)
{
    return true;
}

function gb_suggest_age($value)
{
    return $value;
}

function gb_cast_age(&$value)
{
}

?>
