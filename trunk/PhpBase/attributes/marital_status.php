<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute marital_status for Google Base
*/

function gb_validate_marital_status($value)
{
    return true;
}

function gb_suggest_marital_status($value)
{
    return $value;
}

function gb_cast_marital_status(&$value)
{
}

?>
