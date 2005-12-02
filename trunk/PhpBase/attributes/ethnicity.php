<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute ethnicity for Google Base
*/

function gb_validate_ethnicity($value)
{
    return true;
}

function gb_suggest_ethnicity($value)
{
    return $value;
}

function gb_cast_ethnicity(&$value)
{
}

?>
