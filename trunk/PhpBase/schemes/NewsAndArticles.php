<?php

$schema = array(
'_attributes' => array('title','description','link', 'id', 'author', 'news_source', 'publish_date','expiration_date','expiration_date_time','label','pages','publish_date','image_link'),
    
'_not_null' => array('title','description','link'),

'_not_zero' => array('title','description','link', 'pages'),

'_default_values' => array(),

'_multiple_choice' => array(),
);

?>
