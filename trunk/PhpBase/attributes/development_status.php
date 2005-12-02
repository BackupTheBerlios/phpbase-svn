<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute development_status for Google Base
*/

function gb_validate_development_status($value)
{
    return true;
}

function gb_suggest_development_status($value)
{
    return $value;
}

function gb_cast_development_status(&$value)
{
}

?>
