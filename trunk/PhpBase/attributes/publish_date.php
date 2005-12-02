<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute publish_date for Google Base
*/

function gb_validate_publish_date($value)
{
    return true;
}

function gb_suggest_publish_date($value)
{
    return $value;
}

function gb_cast_publish_date(&$value)
{
}

?>
