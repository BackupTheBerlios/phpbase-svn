<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute from_location for Google Base
*/

function gb_validate_from_location($value)
{
    return true;
}

function gb_suggest_from_location($value)
{
    return $value;
}

function gb_cast_from_location(&$value)
{
}

?>
