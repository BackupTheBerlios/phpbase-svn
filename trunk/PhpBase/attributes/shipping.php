<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute shipping for Google Base
*/

function gb_validate_shipping($value)
{
    return true;
}

function gb_suggest_shipping($value)
{
    return $value;
}

function gb_cast_shipping(&$value)
{
}

?>
