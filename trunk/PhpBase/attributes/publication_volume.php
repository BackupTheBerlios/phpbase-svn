<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute publication_volume for Google Base
*/

function gb_validate_publication_volume($value)
{
    return true;
}

function gb_suggest_publication_volume($value)
{
    return $value;
}

function gb_cast_publication_volume(&$value)
{
}

?>
