<?php
    
/**
* Copyright 2005 Akelos Media (Bermi Ferrer)
* 
* This are some static functions to handle default attribute currency for Google Base
*/

function gb_validate_currency($value)
{
    return in_array(strtolower($value), 
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
    'wst','xaf','xag','xau','xcd','xdr','xof','xpd','xpf','xpt','yer','zar','zmk','zwd'));
}

function gb_suggest_currency($value)
{
    /**
     * @todo Improve currency suggestion
     */
    $suggestions = array('$'=> 'usd','€'=> 'eur','£'=> 'gbp','¥' => 'jpy');
    $value = strtolower(str_replace(array_keys($suggestions), array_values($suggestions), $value));
    return empty($value) ? 'usd' : $value;
}

function gb_cast_currency(&$value)
{
    $value = strtolower(empty($value) ? 'usd' : $value);
}


?>
