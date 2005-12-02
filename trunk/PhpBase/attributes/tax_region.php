<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute tax_region for Google Base
*/

function gb_validate_tax_region($value)
{
    return true;
}

function gb_suggest_tax_region($value)
{
    return $value;
}

function gb_cast_tax_region(&$value)
{
}

?>
