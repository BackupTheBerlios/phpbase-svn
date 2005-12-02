<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute course_number for Google Base
*/

function gb_validate_course_number($value)
{
    return true;
}

function gb_suggest_course_number($value)
{
    return $value;
}

function gb_cast_course_number(&$value)
{
}

?>
