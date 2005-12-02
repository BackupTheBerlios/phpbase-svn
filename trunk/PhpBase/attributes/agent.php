<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute agent for Google Base
*/

function gb_validate_agent($value)
{
    return true;
}

function gb_suggest_agent($value)
{
    return $value;
}

function gb_cast_agent(&$value)
{
}

?>
