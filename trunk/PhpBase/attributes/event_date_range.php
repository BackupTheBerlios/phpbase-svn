<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute event_date_range for Google Base
*/

function gb_validate_event_date_range($value)
{
    return true;
}

function gb_suggest_event_date_range($value)
{
    return $value;
}

function gb_cast_event_date_range(&$value)
{
}

?>
