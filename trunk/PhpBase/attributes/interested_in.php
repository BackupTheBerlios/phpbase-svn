<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute interested_in for Google Base
*/

function gb_validate_interested_in($value)
{
    return true;
}

function gb_suggest_interested_in($value)
{
    return $value;
}

function gb_cast_interested_in(&$value)
{
}

?>
