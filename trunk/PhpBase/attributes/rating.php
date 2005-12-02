<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute rating for Google Base
*/

function gb_validate_rating($value)
{
    return true;
}

function gb_suggest_rating($value)
{
    return $value;
}

function gb_cast_rating(&$value)
{
}

?>
