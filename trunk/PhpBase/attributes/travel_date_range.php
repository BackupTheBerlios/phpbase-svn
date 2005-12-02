<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute travel_date_range for Google Base
*/

function gb_validate_travel_date_range($value)
{
    return true;
}

function gb_suggest_travel_date_range($value)
{
    return $value;
}

function gb_cast_travel_date_range(&$value)
{
}

?>
