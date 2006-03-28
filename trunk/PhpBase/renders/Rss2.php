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
    var $shared_rss2_attributes = array('title','description','link');
    var $channel_details = array();

    function Rss2Render($channel_details = array())
    {
        $_empty_channel_details = array_diff(array_values($shared_rss2_attributes),array_keys($channel_details));
        if(empty($_empty_channel_details)){
            $this->channel_details = $channel_details;
        }else {
            trigger_error(
            '<h1>Ooops!, You forgot to set the details ('.join(', ',$_empty_channel_details).') for the Rss2 channel.</h1>
            <pre>'.
            'call $PhpBase->send(\'user_name\', \'password\', \'file_name.xml\', '.
            '    array('.
            '        \'format\'=>\'Rss2\', '.
            '        \'title\'=> \'My Rss2 channel title\', '.
            '        \'description\'=> \'My Rss2 channel description\', '.
            '        \'link\'=> \'My Rss2 channel link\''.
            '    )
            );
            </pre>
            ');
        }
    }

    function render($data_rows)
    {
        $has_custom_attributes = false;
        $rss2_items = '';

        foreach ($data_rows as $data_row){
            $_tmp_columns = $_tmp_heading_columns = array();

            foreach ($this->schema as $column){
                $column_name = $column[1] == ':' ? substr($column,2) : $column;

                if(!in_array($column, $this->shared_rss2_attributes)){
                    if($column[1] == ':'){
                        $has_custom_attributes = true;
                        $column = $column;
                    }else{
                        $column = 'g:'.$column;
                    }
                    $_tmp_columns[] = "<$column>".(isset($data_row->$column_name) ? $data_row->$column_name : '')."</$column>";
                }else{
                    $_tmp_heading_columns[array_shift(array_keys($this->shared_rss2_attributes,$column))] = "<$column>".$data_row->$column_name ."</$column>";
                }
            }
            ksort($_tmp_heading_columns);
            $rss2_items .= "\n<item>\n".implode("\n",array_merge($_tmp_heading_columns,$_tmp_columns))."\n</item>\n";
        }
        $custom_namespace = $has_custom_attributes ? "\nxmlns:c=\"http://base.google.com/cns/1.0\"" : '';

        return
        "<?xml version=\"1.0\"?>".
        "<rss version=\"2.0\">".
        "<channel xmlns:g=\"http://base.google.com/ns/1.0\" $custom_namespace>".
        "<title>{$this->channel_details['title']}</title>".
        "<link>{$this->channel_details['link']}</link>".
        "<description>{$this->channel_details['description']}</description>".
        $rss2_items.
        "</channel>".
        "</rss>";
    }


}


?>
