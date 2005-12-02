<?php

/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute payment_accepted for Google Base
*/

function gb_validate_payment_accepted($value)
{
    if(empty($value) || is_array($value)){
        return false;
    }
    $value = strtolower($value);
    $values = strstr($value,',') ? explode(',',$value) : array($value);
    return (count(array_diff($values,array('cash','check','visa','mastercard','american express','discover','wire transfer'))) === 0);
}

function gb_suggest_payment_accepted($value)
{
    $value = strtolower(is_array($value) ? join(',',$value) : $value);
    $values = strstr($value,',') ? explode(',',$value) : array($value);
    return str_replace(', ',',', ucwords(empty($value) ? 'cash' : join(', ',array_intersect($values,array('cash','check','visa','mastercard','american express','discover','wire transfer')))));
}

function gb_cast_payment_accepted(&$value)
{
    $value = empty($value) ? 'cash' : $value;
    $value = is_array($value) ? join(',',$value) : $value;
    $value = trim(str_replace(array('  ',', ',' ,'),array(' ',',',','),
    ucwords(str_replace(',',' , ', is_array($value) ? join(',',$value) : $value))));
}

?>
