<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute occupation for Google Base
*/

function gb_validate_occupation($value)
{
    return true;
}

function gb_suggest_occupation($value)
{
    return $value;
}

function gb_cast_occupation(&$value)
{
}

?>
