<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute programming_language for Google Base
*/

function gb_validate_programming_language($value)
{
    return true;
}

function gb_suggest_programming_language($value)
{
    return $value;
}

function gb_cast_programming_language(&$value)
{
}

?>
