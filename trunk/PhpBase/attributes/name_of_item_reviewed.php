<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute name_of_item_reviewed for Google Base
*/

function gb_validate_name_of_item_reviewed($value)
{
    return true;
}

function gb_suggest_name_of_item_reviewed($value)
{
    return $value;
}

function gb_cast_name_of_item_reviewed(&$value)
{
}

?>
