<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute tax_percent for Google Base
*/

function gb_validate_tax_percent($value)
{
    return true;
}

function gb_suggest_tax_percent($value)
{
    return $value;
}

function gb_cast_tax_percent(&$value)
{
}

?>
