<?php

/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute price for Google Base
*/

function gb_validate_price($value)
{
    return empty($value) ? ($value != '' && intval($value) === 0 ? false : true) : is_numeric($value);
}

function gb_suggest_price($value)
{
    $value = preg_replace('/[^0-9]+/','', $value);
    return $value == 0 ? '' : $value;
}

function gb_cast_price(&$value)
{
    $value = preg_replace('/[^0-9^\.]+/','', $value);
    $value = number_format($value,2,'.','');
    $value = $value == 0.00 ? '' : $value;
}


$errores = array('price','location');
$campos = array('price'=>20,'agent'=>'Bermi','location'=>'Carlet');


?>
