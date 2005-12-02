<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute model_number for Google Base
*/

function gb_validate_model_number($value)
{
    return true;
}

function gb_suggest_model_number($value)
{
    return $value;
}

function gb_cast_model_number(&$value)
{
}

?>
