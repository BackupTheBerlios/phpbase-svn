<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute course_date_range for Google Base
*/

function gb_validate_course_date_range($value)
{
    return true;
}

function gb_suggest_course_date_range($value)
{
    return $value;
}

function gb_cast_course_date_range(&$value)
{
}

?>
