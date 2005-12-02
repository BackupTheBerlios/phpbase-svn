<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute to_location for Google Base
*/

function gb_validate_to_location($value)
{
    return true;
}

function gb_suggest_to_location($value)
{
    return $value;
}

function gb_cast_to_location(&$value)
{
}

?>
