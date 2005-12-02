<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute isbn for Google Base
*/

function gb_validate_isbn($value)
{
    return true;
}

function gb_suggest_isbn($value)
{
    return $value;
}

function gb_cast_isbn(&$value)
{
}

?>
