<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
* @file GoogleBaseItem.php
* GoogleBaseItem is the generic Item handling class. You can extend this class 
* to create custom items.
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
!defined('PHPBASE_DIR') ? define('PHPBASE_DIR', str_replace('PhpBase'.DS.'GoogleBaseItem.php','',__FILE__)) : null;

// ---- Required Files ---- //
require_once PHPBASE_DIR.'PhpBase'.DS.'GoogleBaseItemValidator.php';



/**
* GoogleBaseItem
*
* This class provides basic methos for setting and validating single data registries
*
* @package phpbase
* @author Bermi Ferrer <bermi . akelos -com>
* @copyright Copyright (c) 2002-2005, Akelos Media, S.L. http://www.akelos.com
* @license GNU Lesser General Public License <http://www.gnu.org/copyleft/lesser.html>
*/
class GoogleBaseItem
{

    // {{{ properties


    // --- Private properties --- //

    /**
     * Place holder for current schema validator
     *
     * @var GoogleBaseItemValidator
     */
    var $_Validator;

    /**
     * If set to true it will try to automatically suggest a value for given attribute 
     * automatically if validation fails with initial value.
     * This is done using the gb_ATTRIBUTENAME_cast hook function located at attributes/ATTRIBUTENAME.php
     *
     * @var bool
     */
    var $_suggestAutomatically = true;

    /**
     * If set to true it will cast attribute values using the gb_ATTRIBUTENAME_cast hook function
     * automatically if validation fails with initial value.
     * This hook is defined on attributes/ATTRIBUTENAME.php
     *
     * @var bool
     */
    var $_castAutomatically = true;

    // }}}



    // ------ CLASS METHODS ------ //


    // ---- Public methods ---- //


    // {{{ GoogleBaseItem()

    /**
     * Class constructor
     * 
     * A valid Schema Name must be given. Schemas are located at schemes/ folder
     *
     * @param string $schema_name
     */
    function GoogleBaseItem($schema_name = 'Default')
    {
        $this->_Validator =& new GoogleBaseItemValidator($schema_name);
    }

    // }}}
    // {{{ setAttribute()

    /**
     * Sets a value for given attribute.
     * 
     * This method handles sets an attribute value when the following conditions occur:
     * - The value will be casted using the hook function gb_ATTRIBUTENAME_cast located at attributes/ATTRIBUTENAME.php. 
     * If you don't want to cast your values, you can set GoogleBaseItem::_castAutomatically to false.
     * - Attribute value is valid according to validation hook function  gb_ATTRIBUTENAME_validate located at attributes/ATTRIBUTENAME.php
     * - If attribute value is NOT valid it will try to suggest a new value according to your input
     * using the hook function gb_ATTRIBUTENAME_suggest located at attributes/ATTRIBUTENAME.php. 
     * Suggestion can be turned off by setting GoogleBaseItem::_suggestAutomatically to false.
     * Suggested values will be validated again before being set.
     * - If there was an error while setting the attribute, the function will return false and 
     * fields with errors will be accessible by calling GoogleBaseItem::getFieldsWithErrors
     * 
     * @see setAttributes
     * @param string $attribute_name This can be a name from current schema or a custom attribute
     * @param string $value
     * @return bool Returns true if attribute has been added successfully false if validation and suggestion failed.
     */
    function setAttribute($attribute_name, $value)
    {
        $this->_castAutomatically ? $this->_Validator->cast($attribute_name, $value) : null;

        if($this->_Validator->validate($attribute_name, $value)){
            $this->$attribute_name = $value;
            return true;
        }elseif ($this->_suggestAutomatically){
            $suggestion = $this->_Validator->suggest($attribute_name, $value);
            if($this->_Validator->validate($attribute_name, $suggestion)){
                $this->$attribute_name = $suggestion;
                return true;
            }
        }
        $this->_Validator->addError($attribute_name);
        return false;
    }

    // }}}
    // {{{ getAttribute()

    /**
     * Gets an attribute value
     * 
     * @param string $attribute_name This can be a name from current schema or a custom attribute
     * @return mixed
     */
    function getAttribute($attribute_name)
    {
        return isset($this->$attribute_name) ? $this->$attribute_name : null;
    }

    // }}}
    // {{{ setAttributes()

    /**
     * Sets multiple attributes from an array
     *
     * Calls GoogleBaseItem::setAttribute for each array element
     * 
     * @see setAttribute
     * @param array $attributes Array with attributes
     * @return bool False if there are errors
     */
    function setAttributes($attributes)
    {
        foreach ($this->getAvailableAttributes() as $attribute_name){
            $this->setAttribute($attribute_name, @$attributes[$attribute_name]);
            unset($attributes[$attribute_name]);
        }
        if(!empty($attributes)){
            foreach ($attributes as $attribute=>$value){
                $this->setAttribute($attribute, $value);
            }
        }
        return !$this->hasErrors();
    }

    // }}}
    // {{{ getAttributes()

    /**
     * Gets an associative attribute_name=>value array for current Item
     *
     * @return array
     */
    function getAttributes()
    {
        $attributes_array = array();
        foreach (array_merge($this->getAvailableAttributes(), $this->getCustomAttributesNames()) as $attribute){
            $attributes_array[$attribute] = !empty($this->$attribute) ? $this->$attribute : '';
        }
        return $attributes_array;
    }

    // }}}
    // {{{ getAvailableAttributes()

    /**
     * Gets an array with default attribute names from specified schema
     *
     * Gets an array with default attribute names from specified schema.
     * Only attributes defined on the schemes/SchemaName.php file will be included.
     * If you need to override a default schema, you can use setAvailableAttributes()
     * 
     * @see setAvailableAttributes
     * @return array array with values fron current schema
     */
    function getAvailableAttributes()
    {
        return $this->_Validator->_attributes;
    }

    // }}}
    // {{{ setAvailableAttributes()

    /**
     * Sets current schema default attribute names
     * 
     * @see getAvailableAttributes
     * @param $schema_attribute_names
     */
    function setAvailableAttributes($schema_attribute_names)
    {
        $this->_Validator->setAvailableAttributes($schema_attribute_names);
    }

    // }}}
    // {{{ hasAttribute()

    /**
     * Returns true if attribute name is on schema definition
     *
     * @param string $attribute_name
     * @return bool
     */
    function hasAttribute($attribute_name)
    {
        return in_array($attribute_name, $this->_Validator->_attributes);
    }

    // }}}
    // {{{ getCustomAttributesNames()

    /**
     * Gets an array of attributes used for current item that are not available on the default schema
     *
     * @return array Array with custom attribute names
     */
    function getCustomAttributesNames()
    {
        $attributes = get_object_vars($this);
        foreach (array_keys($attributes) as $attribute){
            if($attribute{0} == '_'){
                unset($attributes[$attribute]);
            }
        }
        return array_values(array_diff(array_keys($attributes), $this->getAvailableAttributes()));
    }

    // }}}
    // {{{ hasErrors()

    /**
     * Returns true if there are errors on this Item
     *
     * @return bool
     */
    function hasErrors()
    {
        return $this->_Validator->hasErrors();
    }

    // }}}
    // {{{ getFieldsWithErrors()

    /**
     * Gets an array of attribute names with errors
     *
     * @return array
     */
    function getFieldsWithErrors()
    {
        return $this->_Validator->getFieldsWithErrors();
    }

    // }}}

}

?>