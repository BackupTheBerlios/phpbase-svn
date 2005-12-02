<?php

/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute label for Google Base
*/

function gb_validate_label($value)
{
    if(empty($value)){
        return true;
    }elseif (is_array($value)){
        return false;
    }
    //$value = is_array($value) ? join(',',$value) : $value;
    if(strstr($value,',')){
        $labels = explode(',',$value);
        $number_of_labels = count($labels);
        if($number_of_labels > 10 || $number_of_labels != count(array_unique($labels))){
            return false;
        }
        foreach ($labels as $label){
            if(strlen($label) > 40){
                return false;
            }
        }
    }elseif(strlen($value) > 40){
        return false;
    }
    return true;
}

function gb_suggest_label($value)
{
    $values = is_array($value) ? $value : (strstr($value,',') ? explode(',',$value) : array($value));
    $suggested_values = array();
    $values = array_map('trim',$values);
    $values = array_chunk(array_unique($values),10);
    $suggested_values = array_filter($values[0],'gb_validate_label');
    return join(',',array_diff($suggested_values, array('')));
}

function gb_cast_label(&$value)
{
    $value = empty($value) ? '' : (is_array($value) ? join(',',$value) : $value);
    $value = is_array($value) ? join(',',$value) : $value;
    $value = trim(str_replace(array('  ',', ',' ,'),array(' ',',',','),
    str_replace(',',' , ', is_array($value) ? join(',',$value) : $value)));
}

?>
