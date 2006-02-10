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
!defined('PHPBASE_DIR') ? define('PHPBASE_DIR', str_replace('PhpBase'.DS.'renders'.DS.'Rss2.php','',__FILE__)) : null;

require_once PHPBASE_DIR.'PhpBase'.DS.'GoogleBaseRender.php';

class Rss2Render extends GoogleBaseRender
{
    function render($data_rows)
    {
        $has_custom_attributes = false;
        $rss2_items = '';

        if(isset($data_rows[0]['title'])){
            $rss2_details = array_shift(isset($data_rows));
        }

        foreach ($data_rows as $data_row){
            $_tmp_columns = array();
            foreach ($this->schema as $column){
                $column_name = $column[1] == ':' ? substr($column,2) : $column;
                if($column != 'title' && $column != 'description' && $column != 'link'){
                    if($column[1] == ':'){
                        $has_custom_attributes = true;
                        $column = $column;
                    }else{
                        $column = 'g:'.$column;
                    }
                }
                $_tmp_columns[] = "<$column>".(isset($data_row->$column_name) ? $data_row->$column_name : '')."</$column>";
            }

            $rss2_items .= "\n<item>\n".implode("\n",$_tmp_columns)."\n</item>\n";
        }
        $custom_namespace = $has_custom_attributes ? "\nxmlns:c=\"http://base.google.com/cns/1.0\"" : '';

        return 
        "<?xml version=\"1.0\"?>".
        "<rss version=\"2.0\">".
        "<channel xmlns:g=\"http://base.google.com/ns/1.0\" $custom_namespace>".
        "<title>{$rss2_details['title']}</title>".
        "<link>{$rss2_details['link']}</link>".
        "<description>{$rss2_details['description']}</description>".
        $rss2_items.
        "</channel>".
        "</rss>";
    }


}


?>
