<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute operating_system for Google Base
*/

function gb_validate_operating_system($value)
{
    return true;
}

function gb_suggest_operating_system($value)
{
    return $value;
}

function gb_cast_operating_system(&$value)
{
}

?>
