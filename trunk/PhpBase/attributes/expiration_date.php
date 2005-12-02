<?php

/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute expiration_date for Google Base
*/

function gb_validate_expiration_date($value)
{
    if(!empty($value)){
        if(!preg_match('/^([0-9]{4})-?([0-9]{1,2})-?([0-9]{1,2})$/', $value, $match)){
            return false;
        }
        return checkdate($match[2], $match[3], $match[1]);
    }
    return true;
}

function gb_suggest_expiration_date($value)
{
    return date('Y-m-d', is_string($value) ? strtotime($value) : (is_integer($value) ? $value : time()+2592000));
}

function gb_cast_expiration_date(&$value)
{
    $value = is_integer($value) ? date('Y-m-d',$value) : $value;
}

?>
