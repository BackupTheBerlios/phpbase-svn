<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute megapixels for Google Base
*/

function gb_validate_megapixels($value)
{
    return true;
}

function gb_suggest_megapixels($value)
{
    return $value;
}

function gb_cast_megapixels(&$value)
{
}

?>
