<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute artist for Google Base
*/

function gb_validate_artist($value)
{
    return true;
}

function gb_suggest_artist($value)
{
    return $value;
}

function gb_cast_artist(&$value)
{
}

?>
