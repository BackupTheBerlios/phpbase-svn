<?php

error_reporting(E_ALL);

require_once '../PhpBase/GoogleBaseRender.php';

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');


class Test_of_GoogleBaseRender extends UnitTestCase
{
    var $GoogleBaseRender;
    var $item_id = 1;
    
    function setUp()
    {
        $this->GoogleBaseRender = new GoogleBaseRender();
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
        unset($this->GoogleBaseRender);
    }
    
    function test_of__getColumnsArray()
    {
        $this->assertEqual($this->GoogleBaseRender->_getColumnsArray(
        array('description','education'),array('is_self_employed','salary_expectation')),
        array('description','education','c:is_self_employed','c:salary_expectation'));
    }
    
    function test_of__getColumnsArray_with_field_casting()
    {
        //Not allowed on standard fields
        $this->GoogleBaseRender->_custom_field_types['education'] = 'int';
        //Not set if type does not match
        $this->GoogleBaseRender->_custom_field_types['salary_expectation'] = 'integer';
        $this->GoogleBaseRender->_custom_field_types['is_self_employed'] = 'boolean';
        
        $this->assertEqual($this->GoogleBaseRender->_getColumnsArray(
        array('description','education'),array('is_self_employed','salary_expectation')),
        array('description','education','c:is_self_employed:boolean','c:salary_expectation'));
    }
    
    function test_of_setSchema()
    {
        $this->GoogleBaseRender->setSchema(array('description','education', 'is_self_employed','salary_expectation'));
        $this->assertEqual($this->GoogleBaseRender->schema, array ('description','education','c:is_self_employed','c:salary_expectation'));
    }
    
    function test_of_setSchema_with_custom_field_types()
    {
        $this->GoogleBaseRender->setSchema(array('description','education', 'is_self_employed','salary_expectation'),array('is_self_employed'=>'boolean','education'=>'string'));
        $this->assertEqual($this->GoogleBaseRender->schema, array ('description','education','c:is_self_employed:boolean','c:salary_expectation'));
    }

}


$test = new Test_of_GoogleBaseRender();
$test->run(new TextReporter());


?>