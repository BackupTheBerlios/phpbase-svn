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
!defined('PHPBASE_DIR') ? define('PHPBASE_DIR', str_replace('PhpBase'.DS.'GoogleBaseRender.php','',__FILE__)) : null;

class GoogleBaseRender
{
    var $google_base_default_attributes = array(
    'actor','age','agent','apparel_type','area','artist','author','bathrooms','bedrooms','brand','color',
    'condition','course_date_range','course_number','course_times','currency','delivery_notes','delivery_radius',
    'description','development_status','education','employer','ethnicity','event_date_range','expiration_date',
    'format','from_location','gender','hoa_dues','id','image_link','immigration_status','interested_in','isbn',
    'job_function','job_industry','job_type','label','language','license','link','listing_type','location','make',
    'manufacturer','manufacturer_id','marital_status','megapixels','memory','mileage','model','model_number',
    'name_of_item_reviewed','news_source','occupation','operating_system','pages','payment_accepted','payment_notes',
    'pickup','price','price_type','processor_speed','product_type','programming_language','property_type',
    'publication_name','publication_volume','publish_date','quantity','rating','review_type','reviewer_type','salary',
    'salary_type','school_district','service_type','sexual_orientation','shipping','size','subject','subject_area',
    'tax_percent','tax_region','title','to_location','travel_date_range','upc','university','url_of_item_reviewed',
    'vehicle_type','vin','weight','year','square_footage');

    var $google_base_types = array('string','int','float','intUnit','floatUnit','date','dateTime','dateTimeRange','url','boolean','location');

    var $schema = array();
    
    var $_custom_field_types = array();

    function setSchema($schema, $custom_field_types = null)
    {
        $this->_custom_field_types = empty($custom_field_types) || !is_array($custom_field_types) ? array() : $custom_field_types;

        $schema = array_unique($schema);
        $custom_fields = (array)array_diff($schema, $this->google_base_default_attributes);
        $standard_fields = (array)array_diff($schema,$custom_fields);

        $this->schema = $this->_getColumnsArray($standard_fields, $custom_fields);
        
        return $this->schema;

    }
    

    function _getColumnsArray($standard_fields, $custom_fields)
    {
        foreach ($custom_fields as $k=>$custom_field){
            $field_type = isset($this->_custom_field_types[$custom_field]) && in_array($this->_custom_field_types[$custom_field], $this->google_base_types)
            ? ':'.$this->_custom_field_types[$custom_field] : '';
            $custom_fields[$k] = 'c:'.$custom_field.$field_type;
        }

        return array_merge($standard_fields, $custom_fields);
    }
}


?>