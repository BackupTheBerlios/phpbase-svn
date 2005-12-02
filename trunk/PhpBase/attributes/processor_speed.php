<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute processor_speed for Google Base
*/

function gb_validate_processor_speed($value)
{
    return true;
}

function gb_suggest_processor_speed($value)
{
    return $value;
}

function gb_cast_processor_speed(&$value)
{
}

?>
