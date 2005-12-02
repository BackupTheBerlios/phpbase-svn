<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute delivery_notes for Google Base
*/

function gb_validate_delivery_notes($value)
{
    return true;
}

function gb_suggest_delivery_notes($value)
{
    return $value;
}

function gb_cast_delivery_notes(&$value)
{
}

?>
