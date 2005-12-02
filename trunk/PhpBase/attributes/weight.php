<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute weight for Google Base
*/

function gb_validate_weight($value)
{
    return true;
}

function gb_suggest_weight($value)
{
    return $value;
}

function gb_cast_weight(&$value)
{
}

?>
