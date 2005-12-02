<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute immigration_status for Google Base
*/

function gb_validate_immigration_status($value)
{
    return true;
}

function gb_suggest_immigration_status($value)
{
    return $value;
}

function gb_cast_immigration_status(&$value)
{
}

?>
