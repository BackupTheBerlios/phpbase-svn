<?php

$schema = array(
'_attributes' => array('title', 'description', 'link', 'image_link', 'id',
    'expiration_date', 'label','price', 'price_type', 'currency',
    'payment_accepted', 'listing_type','property_type','bedrooms',
    'bathrooms','square_footage','school_district',
    'hoa_dues','year','agent','location'),
    
'_not_null' => array('title','description','link','id','price_type','currency', 'listing_type', 'payment_accepted'),

'_not_zero' => array('price','year'),

'_default_values' => array( 'currency' => 'USD', 'price_type' => 'starting', 'payment_accepted' => 'Cash', 'listing_type' => 'For sale'),

'_multiple_choice' => array(
    'currency' =>
    array('aed','afa','all','amd','ang','aoa','ars','aud','awg','azm','bam','bbd','bdt','bgn','bhd',
    'bif','bmd','bnd','bob','brl','bsd','btn','bwp','byr','bzd','cad','cdf','chf','clp','cny','cop',
    'crc','csd','cup','cve','cyp','czk','djf','dkk','dop','dzd','eek','egp','ern','etb','eur','fjd',
    'fkp','gbp','gel','ggp','ghc','gip','gmd','gnf','gtq','gyd','hkd','hnl','hrk','htg','huf','idr',
    'ils','imp','inr','iqd','irr','isk','jep','jmd','jod','jpy','kes','kgs','khr','kmf','kpw','krw',
    'kwd','kyd','kzt','lak','lbp','lkr','lrd','lsl','ltl','lvl','lyd','mad','mdl','mga','mkd','mmk',
    'mnt','mop','mro','mtl','mur','mvr','mwk','mxn','myr','mzm','nad','ngn','nio','nok','npr','nzd',
    'omr','pab','pen','pgk','php','pkr','pln','pyg','qar','ron','rub','rwf','sar','sbd','scr','sdd',
    'sek','sgd','shp','sit','skk','sll','sos','spl','srd','std','svc','syp','szl','thb','tjs','tmm',
    'tnd','top','trl','try','ttd','tvd','twd','tzs','uah','ugx','usd','uyu','uzs','veb','vnd','vuv',
    'wst','xaf','xag','xau','xcd','xdr','xof','xpd','xpf','xpt','yer','zar','zmk','zwd'),

    'payment_accepted' => array('cash','check','visa','mastercard','american express','discover','wire transfer'),

    'listing_type' => array('for sale','rental','sublet','swap','vacation rental'),

    'price_type' => array('negotiable','starting')
    ),
);

?>
