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
!defined('PHPBASE_DIR') ? define('PHPBASE_DIR', str_replace('PhpBase'.DS.'renders'.DS.'Tabbed.php','',__FILE__)) : null;

require_once PHPBASE_DIR.'PhpBase'.DS.'GoogleBaseRender.php';

class TabbedRender extends GoogleBaseRender
{
    function render($data_rows)
    {
        $tabbed_file = implode("\t",$this->schema)."\n";
        foreach ($data_rows as $data_row){
            $_tmp_columns = array();
            foreach ($this->schema as $column){
                $column = $column[1] == ':' ? substr($column,2) : $column;
                $_tmp_columns[] = isset($data_row->$column) ? $data_row->$column : '';
            }

            $tabbed_file .= implode("\t",$_tmp_columns)."\n";
        }
        
        return $tabbed_file;
    }
    
    
}


?>