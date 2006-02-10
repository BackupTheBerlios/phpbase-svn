<?php

/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute expiration_date_time for Google Base
*/

function gb_validate_expiration_date_time($value)
{
    if(!empty($value)){
        if(!preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/", $value, $match)){
            return false;
        }

        return  count($match) == 7 &&
                ($match[4] < 24 && $match[4] >= 0) && 
                ($match[5] < 60 && $match[5] >= 0) && 
                ($match[6] < 60 && $match[6] >= 0) && 
                checkdate($match[2], $match[3], $match[1]);
    }
    return true;
}

function gb_suggest_expiration_date_time($value)
{
    return date('Y-m-d H:i:s', is_string($value) ? strtotime($value) : (is_integer($value) ? $value : time()+2592000));
}

function gb_cast_expiration_date_time(&$value)
{
    $value = is_integer($value) ? date('Y-m-d H:i:s',$value) : $value;
}

?>
