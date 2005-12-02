<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute gender for Google Base
*/

function gb_validate_gender($value)
{
    return true;
}

function gb_suggest_gender($value)
{
    return $value;
}

function gb_cast_gender(&$value)
{
}

?>
