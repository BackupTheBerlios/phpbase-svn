<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute service_type for Google Base
*/

function gb_validate_service_type($value)
{
    return true;
}

function gb_suggest_service_type($value)
{
    return $value;
}

function gb_cast_service_type(&$value)
{
}

?>
