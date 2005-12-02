<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute reviewer_type for Google Base
*/

function gb_validate_reviewer_type($value)
{
    return true;
}

function gb_suggest_reviewer_type($value)
{
    return $value;
}

function gb_cast_reviewer_type(&$value)
{
}

?>
