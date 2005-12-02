<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute employer for Google Base
*/

function gb_validate_employer($value)
{
    return true;
}

function gb_suggest_employer($value)
{
    return $value;
}

function gb_cast_employer(&$value)
{
}

?>
