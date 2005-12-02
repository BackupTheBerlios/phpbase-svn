<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute license for Google Base
*/

function gb_validate_license($value)
{
    return true;
}

function gb_suggest_license($value)
{
    return $value;
}

function gb_cast_license(&$value)
{
}

?>
