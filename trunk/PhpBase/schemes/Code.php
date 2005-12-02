<?php

$schema = array(
'_attributes' => array('title','description','link','id','development_status', 'license', 'operating_system', 'programming_language', 'publish_date'),
    
'_not_null' => array('title','description','link'),

'_not_zero' => array('title','description','link'),

'_default_values' => array(),

'_multiple_choice' => array(),
);

?>
