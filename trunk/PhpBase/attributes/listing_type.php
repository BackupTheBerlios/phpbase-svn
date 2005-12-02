<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute listing_type for Google Base
*/

function gb_validate_listing_type($value)
{
    return in_array(strtolower($value), array('for sale','rental','sublet','swap','vacation rental'));
}

function gb_suggest_listing_type($value)
{
    return (strstr($value,'sale') || strstr($value,'build') ) ? 'for sale' : ((strstr($value,'holiday')) ? 'vacation rental' : (strstr($value,'rent') ? 'rental' : 'for sale'));
}

function gb_cast_listing_type(&$value)
{
    $value = strtolower(empty($value) ? 'for sale' : $value);
}

?>
