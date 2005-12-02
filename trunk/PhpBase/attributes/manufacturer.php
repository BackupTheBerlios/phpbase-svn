<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute manufacturer for Google Base
*/

function gb_validate_manufacturer($value)
{
    return true;
}

function gb_suggest_manufacturer($value)
{
    return $value;
}

function gb_cast_manufacturer(&$value)
{
}

?>
