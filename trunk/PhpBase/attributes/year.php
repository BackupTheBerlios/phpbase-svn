<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute year for Google Base
*/

function gb_validate_year($value)
{
    return empty($value) ? true : (is_numeric($value) && strlen($value) == 4);
}

function gb_suggest_year($value)
{
    gb_cast_year($value);
    return empty($value) ? '' : $value;
}

function gb_cast_year(&$value)
{
    $value = is_integer($value) && strlen($value) > 4 ? date('Y',$value) : $value;
}

?>
