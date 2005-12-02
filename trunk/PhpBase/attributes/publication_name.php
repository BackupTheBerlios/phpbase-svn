<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute publication_name for Google Base
*/

function gb_validate_publication_name($value)
{
    return true;
}

function gb_suggest_publication_name($value)
{
    return $value;
}

function gb_cast_publication_name(&$value)
{
}

?>
