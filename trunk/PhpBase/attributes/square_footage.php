<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute square_footage for Google Base
*/

function gb_validate_square_footage($value)
{
    return empty($value) ? true : (is_numeric($value) && $value > 0);
}

function gb_suggest_square_footage($value)
{
    return empty($value) ? '' : number_format($value,0,'','');
}

function gb_cast_square_footage(&$value)
{
}

?>
