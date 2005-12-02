<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute location for Google Base
*/

function gb_validate_location($value)
{
    return true;
}

function gb_suggest_location($value)
{
    return $value;
}

function gb_cast_location(&$value)
{
}

?>
