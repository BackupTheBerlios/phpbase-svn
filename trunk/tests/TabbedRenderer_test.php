<?php

error_reporting(E_ALL);

require_once '../PhpBase.php';
require_once '../PhpBase/renders/Tabbed.php';

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');


class Test_of_TabbedRender extends UnitTestCase
{
    var $TabbedRender;
    var $item_id = 1;
    
    function setUp()
    {
        $this->TabbedRender = new TabbedRender();
    }
    
    function incrementTestItem()
    {
        $this->sample_item = array (
        'title' => 'Rental house in Carlet',
        'description' => 'Beautiful house with views to the horta.',
        'link' => 'http://inmoeasy.com/property/'.$this->item_id,
        'image_link' => array('http://inmoeasy.com/property/'.$this->item_id.'/image','http://inmoeasy.com/property/'.$this->item_id.'/image2'),
        'id' => $this->item_id,
        'expiration_date' => '2006-12-20',
        'label' => 'house,rental hose,spanish rental,carlet,vacation house',
        'price' => '500.00',
        'price_type' => 'negotiable',
        'currency' => 'eur',
        'payment_accepted' => 'Cash,Wire Transfer',
        'listing_type' => 'for sale',
        'property_type' => 'Town House',
        'bedrooms' => 4,
        'bathrooms' => 3,
        'square_footage' => 400,
        'school_district' => '',
        'hoa_dues' => '',
        'year' => 1985+$this->item_id,
        'agent' => 'OpenHabitat',
        'location' => 'Carlet, Valencia, Spain',
        );
        $this->item_id++;
    }

    function tearDown()
    {
        unset($this->TabbedRender);
    }
    
    
    
    
}


$test = new Test_of_TabbedRender();
$test->run(new TextReporter());


?>