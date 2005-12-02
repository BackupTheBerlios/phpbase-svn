<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
* @file GoogleBaseItemValidator.php
* GoogleBaseItemValidator is the generic Item validation class.
*/

// +----------------------------------------------------------------------+
// | PhpBase (PHP Library for integrating data sources with Google Base)  |
// +----------------------------------------------------------------------+
// | Copyright (C) 2005  Bermi Ferrer Martinez                            |
// | Released under the GNU Lesser General Public License                 |
// +----------------------------------------------------------------------+
// | You should have received the following files along with this library |
// | - LICENSE (LGPL License)                                             |
// | - CREDITS (Developpers, contributors and contact information)        |
// | - README (Important information regarding this library)              |
// +----------------------------------------------------------------------+

!defined('DS') ? define('DS', DIRECTORY_SEPARATOR) : null;
!defined('PHPBASE_DIR') ? define('PHPBASE_DIR', str_replace('PhpBase'.DS.'GoogleBaseItemValidator.php','',__FILE__)) : null;


class GoogleBaseItemValidator
{
    var $_schema = array();
    var $_not_null = array();
    var $_not_zero = array();
    var $_dont_cast = array();
    var $_dont_suggest = array();
    var $_default_values = array();
    var $_multiple_choice = array();

    var $_suggestions = array();
    var $_errors = array();


    function GoogleBaseItemValidator($schema_name = 'Default')
    {
        static $settings_cache;

        if(empty($settings_cache[$schema_name])){
            include(PHPBASE_DIR.'PhpBase'.DS.'schemes'.DS.$schema_name.'.php');
            $settings_cache[$schema_name] = $schema;

            foreach ($schema['_attributes'] as $attribute){
                require_once(PHPBASE_DIR.'PhpBase'.DS.'attributes'.DS.$attribute.'.php');
            }
        }

        foreach (array_keys($settings_cache[$schema_name]) as $k){
            $this->$k = $settings_cache[$schema_name][$k];
        }
    }


    function validate($attribute, $value)
    {
        if($value !== '' && in_array($attribute, $this->_not_zero) && abs($value) === 0){
            return false;
        }
        if((in_array($attribute, $this->_not_null) && empty($value))){
            return false;
        }

        $validate_function = 'gb_validate_'.$attribute;
        if(function_exists($validate_function)){
            return $validate_function($value);
        }else{
            return true;
        }
    }

    function cast($attribute, &$value)
    {
        if(in_array($attribute, $this->_dont_cast)){
            return false;
        }

        $this->genericCast($value);

        $cast_function = 'gb_cast_'.$attribute;
        if(function_exists($cast_function)){
            $cast_function($value);
            return true;
        }else{
            return false;
        }
    }

    function suggest($attribute, $value)
    {
        if(in_array($attribute, $this->_dont_suggest)){
            return $value;
        }
        $suggestion_cache = $this->getSuggestionForAttribute($attribute);
        if(!empty($suggestion_cache)){
            return $suggestion_cache;
        }
        if($this->validate($attribute, $value) || (empty($value) && !in_array($attribute, $this->_not_null))){
            return $value == 0 && in_array($attribute, $this->_not_zero) ? '' : $value;
        }
        $suggest_function = 'gb_suggest_'.$attribute;
        if(function_exists($suggest_function)){
            return $suggest_function($value);
        }
        return $value;
    }

    function setSchema(&$schema)
    {
        $this->_schema =& $schema;
    }

    function genericCast(&$value)
    {
        $value = is_string($value) ? trim(strip_tags(str_replace(array("\n","\t","\r","  "),array(" "," "," "," "),$value))) : $value;
    }

    function addError($attribute)
    {
        $this->_errors[$attribute] = true;
    }
    function clearError($attribute)
    {
        unset($this->_errors[$attribute]);
    }
    function hasErrors()
    {
        return !empty($this->_errors);
    }
    function getFieldsWithErrors()
    {
        return array_keys($this->_errors);
    }
    function addSuggestion($attribute, $suggested_value)
    {
        return $this->_suggestions[$attribute] = $suggested_value;
    }

    function getSuggestion($attribute, $value)
    {
        return empty($this->_suggestions[$attribute]) ? $this->suggest($attribute, $value) : $this->_suggestions[$attribute];
    }

    function getSuggestionForAttribute($attribute)
    {
        return @$this->_suggestions[$attribute];
    }

    function setAttributeOptions($attribute, $options)
    {
        if(!is_array($options)){
            trigger_error('Ooops! $options must be an array on setAttributeOptions',E_WARNING);
        }
        $this->_multiple_choice[$attribute] = $options;
    }

    function setAvailableAttributes($attributes)
    {
        $this->_attributes = $attributes;
    }

    function setAttributeDefaultValue($attribute, $value)
    {
        $this->_default_values[$attribute] = $value;
    }

    function allowNullOnAttribute($attribute)
    {
        $position = array_search($attribute,$this->_not_null);
        if($position !== false){
            unset($this->_not_null[$position]);
        }
    }

    function dontAllowNullOnAttribute($attribute)
    {
        array_push($this->_not_null,$attribute);
        $this->_not_null = array_unique($this->_not_null);
    }

}


?>