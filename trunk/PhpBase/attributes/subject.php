<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute subject for Google Base
*/

function gb_validate_subject($value)
{
    return true;
}

function gb_suggest_subject($value)
{
    return $value;
}

function gb_cast_subject(&$value)
{
}

?>
