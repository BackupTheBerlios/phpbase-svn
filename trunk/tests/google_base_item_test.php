<?php

error_reporting(E_ALL);

require_once '../PhpBase/GoogleBaseItem.php';

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');


class Test_of_GoogleBaseItem extends UnitTestCase
{
    function setUp()
    {
        $this->item =& new GoogleBaseItem('Housing');
        $this->sample_item = array (
        'title' => 'Rental house in Carlet',
        'description' => 'Beautiful house with views to the horta.',
        'link' => 'http://inmoeasy.com/property/403',
        'image_link' => 'http://inmoeasy.com/property/403/image',
        'id' => 'inmoeasy.com,403',
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
        'year' => 1985,
        'agent' => 'OpenHabitat',
        'location' => 'Carlet, Valencia, Spain',
        );
    }

    function tearDown()
    {
        unset($this->item);
    }

    function test_for_validator()
    {
        $this->assertEqual(strtolower(get_class($this->item->_Validator)),'googlebaseitemvalidator');
    }

    function test_of_getAvailableAttributes()
    {
        include('../PhpBase/schemes/Housing.php');
        $this->assertEqual($this->item->getAvailableAttributes(), $schema['_attributes']);
    }

    function test_of_setAvailableAttributes()
    {
        $new_attributes = array('id','title');
        $this->item->setAvailableAttributes($new_attributes);
        $this->assertEqual($this->item->getAvailableAttributes(), $new_attributes);
    }

    function test_of_hasAttribute()
    {
        $this->assertTrue($this->item->hasAttribute('price'));
        $this->assertFalse($this->item->hasAttribute('discount'));
    }

    function test_of_set_and_get_Attribute()
    {
        $this->assertTrue($this->item->setAttribute('price',123));
        $this->assertEqual($this->item->getAttribute('price'), 123.00);

        $this->assertTrue($this->item->setAttribute('price','0 €'));
        $this->assertEqual($this->item->getAttribute('price'), '');
        $this->assertTrue($this->item->setAttribute('price','123 €'));
        $this->assertEqual($this->item->getAttribute('price'), 123.00);

        $this->assertTrue($this->item->setAttribute('payment_accepted',array('cash','check')));
        $this->assertEqual($this->item->getAttribute('payment_accepted'), 'Cash,Check');

        $this->assertTrue($this->item->setAttribute('payment_accepted','cash,check,maestro'));
        $this->assertEqual($this->item->getAttribute('payment_accepted'), 'Cash,Check');
    }

    function test_of_hasErrors_and_getFieldsWithErrors()
    {
        $this->assertFalse($this->item->setAttribute('payment_accepted','maestro'));
        $this->assertTrue($this->item->hasErrors());

        $this->assertEqual($this->item->getFieldsWithErrors(), array('payment_accepted'));
    }


    function test_of_setAttributes_and_getAttributes()
    {
        $attributes = array(
        'title' => 'Rental house in Carlet',
        'description' => 'Beautiful house with views to the horta.',
        'link' => 'http://inmoeasy.com/property/403',
        'image_link' => 'http://inmoeasy.com/property/403/image',
        'id' => 'inmoeasy.com,403',
        'expiration_date' => '2006-12-20',
        'label' => 'house,rental hose,spanish rental,carlet,vacation house',
        'price' => 500,
        'price_type' => 'negotiable',
        'currency' => 'eur',
        'payment_accepted' => 'Cash,Wire Transfer',
        'listing_type' => 'For sale',
        'property_type' => 'Town House',
        'bedrooms' => 4,
        'bathrooms' => 3,
        'square_footage' => 400,
        'year' => 1985,
        'agent' => 'OpenHabitat',
        'location' => 'Carlet, Valencia, Spain');

        $this->assertTrue($this->item->setAttributes($attributes));

        $expected = array (
        'title' => 'Rental house in Carlet',
        'description' => 'Beautiful house with views to the horta.',
        'link' => 'http://inmoeasy.com/property/403',
        'image_link' => 'http://inmoeasy.com/property/403/image',
        'id' => 'inmoeasy.com,403',
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
        'year' => 1985,
        'agent' => 'OpenHabitat',
        'location' => 'Carlet, Valencia, Spain',
        );

        $this->assertEqual($this->item->getAttributes(), $expected);

        $attributes['custom_attribute'] = 123;

        $this->assertTrue($this->item->setAttributes($attributes));

        $expected['custom_attribute'] = 123;

        $this->assertEqual($this->item->getAttributes(), $expected);
    }

    function test_getCustomAttributesNames()
    {
        $attributes = array_merge($this->sample_item,array('price'=>123, 'custom'=>'custom value'));
        $this->assertTrue($this->item->setAttributes($attributes));
        $this->assertEqual($this->item->getCustomAttributesNames(), array('custom'));
    }

}

$test = &new Test_of_GoogleBaseItem();
$test->run(new TextReporter());




?>