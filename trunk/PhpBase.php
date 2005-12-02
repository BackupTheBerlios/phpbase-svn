<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
* @file PhpBase.php
* PhpBase is the main class for the PhpBase library. This library assists PHP developers inserting Bulk data into Google Base
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
!defined('PHPBASE_DIR') ? define('PHPBASE_DIR', str_replace('PhpBase.php','',__FILE__)) : null;

require_once PHPBASE_DIR.'PhpBase'.DS.'GoogleBaseItem.php';

class PhpBase
{
    var $_schema_name = 'Default';
    var $_domain = '';
    var $_Items = array();
    var $_errors = array();
    var $_lastError = '';
    var $_customAttributes = array();
    var $_definedAttributes = array();
    var $_appendDomainToId = true;
    var $_rendered;
    var $_renders = array();
    var $_ftp_server = 'uploads.google.com';
    

    function PhpBase($schema_name = 'Default')
    {
        if(file_exists(PHPBASE_DIR.'PhpBase'.DS.'schemes'.DS.$schema_name.'.php')){
            $this->_schema_name = $schema_name;
            $this->setDomain();
            return true;
        }else{
            trigger_error('Scheme file '.$schema_name.'.php does not exist on the schemes/ directory', E_USER_ERROR);
            return false;
        }
    }

    function setDomain($domain = null)
    {
        $domain = !empty($domain) ? $domain : @$_SERVER['SERVER_NAME'];
        $this->_domain = $domain;
    }

    function addItem($single_item_details)
    {
        if($this->_appendDomainToId && !empty($this->_domain) && isset($single_item_details['id'])){
            $single_item_details['id'] = $this->_domain.';'.$single_item_details['id'];
        }
        
        $single_item_details = $this->_normalizeColumnNames($single_item_details);
        
        $Item =& new GoogleBaseItem($this->_schema_name);
        if($Item->setAttributes($single_item_details)){

            if(isset($this->_Items[$Item->id])){
                $this->_errors[] = array('id'=>$Item->id);
                $this->_lastError = 'Trying to add duplicated Id: '.$Item->id;
                return false;
            }

            $this->_Items[$Item->id] =& $Item;
            $this->_addToCustomAttributesUsed($Item->getCustomAttributesNames());
            return $Item;
        }
        $error = $this->_getUserErrorsArray($Item->getFieldsWithErrors(), $single_item_details);
        $this->_errors[] = $error;
        $this->_lastError = 'These fields got validation  errors: '.var_export($error,true);
        return false;
    }
    

    function addItems($data_array)
    {
        if(!is_array($data_array)){
            trigger_error('You must supply a valid data array', E_USER_WARNING);
        }

        foreach ($data_array as $single_item_details){
            $this->addItem($single_item_details);
        }
        return empty($this->_errors);
    }

    function setItems($items)
    {
        $this->_Items = array();
        return $this->addItems($items);
    }

    function getItems()
    {
        return $this->_Items;
    }

    function lastItem()
    {
        if($this->hasItems()){
            $last_item = array_values(array_slice($this->getItems(), -1));
            return $last_item[0];
        }
        return false;
    }

    function countItems()
    {
        return count($this->_Items);
    }

    function hasItem($item_id)
    {
        return !empty($this->_Items[$item_id]);
    }

    function hasItems()
    {
        return !empty($this->_Items);
    }

    function lastError()
    {
        return $this->_lastError;
    }


    function render($format = 'Tabbed', $include_custom_attributes = true)
    {
        $Render = $this->_loadRender($format);
        $Render->setSchema($this->getSchema($include_custom_attributes));
        $this->_rendered = $Render->render($this->_Items);
        return $this->_rendered;
    }
    
    function send($user_name = '', $password = '', $file_name = '')
    {
        $this->_rendered = empty($this->_rendered) ? $this->render() : $this->_rendered;
        
        $file_name = empty($file_name) ? $this->_domain.'_'.$this->_schema_name.'_PhpBase.txt' : $file_name;
        if(!class_exists('AkFtpClient')){
            @include_once(PHPBASE_DIR.'PhpBase'.DS.'AkFtpClient.php');
        }
        if(!defined('GOOGLE_BASE_FTP_SETTINGS')){
            define('GOOGLE_BASE_FTP_SETTINGS','ftp://'.$user_name.':'.$password.'@'.$this->_ftp_server.'/');
        }
        return AkFtpClient::put_contents($file_name, $this->_rendered);
    }
    
    function getAttributesWithProblems()
    {
        return $this->_errors;
    }
    

    function &_loadRender($format)
    {
        $format = ucfirst($format);
        if(!isset($this->_renders[$format])){
            $render_class_name = $format.'Render';
            include_once(PHPBASE_DIR.'PhpBase'.DS.'renders'.DS.$format.'.php');
            if(!class_exists($render_class_name)){
                trigger_error('Could not find render '.$format.' on the renders/ directory', E_USER_ERROR);
            }
            $this->_renders[$format] = new $render_class_name();
        }
        return $this->_renders[$format];
    }

    function _getUserErrorsArray($fields_with_errors, $user_attributes_array)
    {
        $result = array();
        foreach($fields_with_errors as $k => $v){
            $result[$v] = @$user_attributes_array[$v];
        }
        return $result;
    }

    function _addToCustomAttributesUsed($attributes)
    {
        $this->_customAttributes = array_merge($attributes, $this->_customAttributes);
        $this->_customAttributes = array_unique($this->_customAttributes);
    }

    function getCustomAttributesUsed()
    {
        return $this->_customAttributes;
    }

    function getSchema($include_custom_attributes = true)
    {
        $schema = array();
        if (empty($this->_definedAttributes)) {
            if(!empty($this->_Items)){
                $Item = array_shift(array_slice($this->_Items,0,1));
                $this->_definedAttributes = $Item->getAvailableAttributes();
            }else{
                include(PHPBASE_DIR.'PhpBase'.DS.'schemes'.DS.$this->_schema_name.'.php');
                $this->_definedAttributes = $schema['_attributes'];
            }
        }
        return $include_custom_attributes ? array_merge($this->_definedAttributes, $this->_customAttributes) : $this->_definedAttributes;
    }
    
    function _normalizeColumnNames($data_array)
    {
        $normalized = array();
        foreach ($data_array as $column=>$value){
            $column = trim(preg_replace('/_{2,}/','_',preg_replace('/([^a-z^0-9^_])+/','_',strtolower($column))),'_');
            $normalized[$column]  = $value;
        }
        return $normalized;
    }

}



?>