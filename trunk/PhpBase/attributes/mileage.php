<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute mileage for Google Base
*/

function gb_validate_mileage($value)
{
    return true;
}

function gb_suggest_mileage($value)
{
    return $value;
}

function gb_cast_mileage(&$value)
{
}

?>
