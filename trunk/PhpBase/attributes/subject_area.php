<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute subject_area for Google Base
*/

function gb_validate_subject_area($value)
{
    return true;
}

function gb_suggest_subject_area($value)
{
    return $value;
}

function gb_cast_subject_area(&$value)
{
}

?>
