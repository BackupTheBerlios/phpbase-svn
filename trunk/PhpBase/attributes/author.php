<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute author for Google Base
*/

function gb_validate_author($value)
{
    return true;
}

function gb_suggest_author($value)
{
    return $value;
}

function gb_cast_author(&$value)
{
}

?>
