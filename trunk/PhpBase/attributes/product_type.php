<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute product_type for Google Base
*/

function gb_validate_product_type($value)
{
    return true;
}

function gb_suggest_product_type($value)
{
    return $value;
}

function gb_cast_product_type(&$value)
{
}

?>
