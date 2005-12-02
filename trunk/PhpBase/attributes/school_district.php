<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute school_district for Google Base
*/

function gb_validate_school_district($value)
{
    return true;
}

function gb_suggest_school_district($value)
{
    return $value;
}

function gb_cast_school_district(&$value)
{
}

?>
