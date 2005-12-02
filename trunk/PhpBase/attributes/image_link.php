<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute image_link for Google Base
*/

function gb_validate_image_link($value)
{
    if(empty($value)){
        return true;
    }elseif (is_array($value)){
        return false;
    }
    $value = strtolower($value);
    if(!(empty($value) ? true : (substr($value,0,7) == 'http://' || substr($value,0,8) == 'https://'))){
        return false;
    }
    
    if(strstr($value,',')){
        $links = explode(',',$value);
        $number_of_links = count($links);
        if($number_of_links > 10 || $number_of_links != count(array_unique($links))){
            return false;
        }
        foreach ($links as $link){
            if(substr($link,0,7) != 'http://' && substr($link,0,8) != 'https://'){
                return false;
            }
        }
    }
    
    return true;
}

function gb_suggest_image_link($value)
{
    $values = is_array($value) ? $value : (strstr($value,',') ? explode(',',$value) : array($value));
    $suggested_values = array();
    $values = array_map('trim',$values);
    $values = array_chunk(array_unique($values),10);
    foreach ($values[0] as $value){
        $suggested_values[] = !empty($value) && $value{0} != '/' && !gb_validate_image_link($value) ? 'http://'.$value : (gb_validate_image_link($value) ? $value : '');
    }
    return join(',',array_diff($suggested_values, array('')));
}

function gb_cast_image_link(&$value)
{
}

?>
