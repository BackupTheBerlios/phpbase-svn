<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute actor for Google Base
*/

function gb_validate_actor($value)
{
    return true;
}

function gb_suggest_actor($value)
{
    return $value;
}

function gb_cast_actor(&$value)
{
}

?>
