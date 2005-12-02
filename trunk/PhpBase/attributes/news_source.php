<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute news_source for Google Base
*/

function gb_validate_news_source($value)
{
    return true;
}

function gb_suggest_news_source($value)
{
    return $value;
}

function gb_cast_news_source(&$value)
{
}

?>
