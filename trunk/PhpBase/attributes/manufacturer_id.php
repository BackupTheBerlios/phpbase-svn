<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute manufacturer_id for Google Base
*/

function gb_validate_manufacturer_id($value)
{
    return true;
}

function gb_suggest_manufacturer_id($value)
{
    return $value;
}

function gb_cast_manufacturer_id(&$value)
{
}

?>
