<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute vin for Google Base
*/

function gb_validate_vin($value)
{
    return true;
}

function gb_suggest_vin($value)
{
    return $value;
}

function gb_cast_vin(&$value)
{
}

?>
