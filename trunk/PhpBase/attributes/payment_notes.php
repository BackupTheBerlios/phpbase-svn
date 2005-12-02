<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute payment_notes for Google Base
*/

function gb_validate_payment_notes($value)
{
    return true;
}

function gb_suggest_payment_notes($value)
{
    return $value;
}

function gb_cast_payment_notes(&$value)
{
}

?>
