<?php

error_reporting(E_ALL);

require_once '../PhpBase.php';

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');


class Test_of_PhpBase extends UnitTestCase
{
    var $PhpBase;
    var $item_id = 1;
    var $sample_item;
    
    function setUp()
    {
        $this->PhpBase =& new PhpBase('Housing');
        $this->incrementTestItem();
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
        unset($this->PhpBase);
    }

    function test_for_constructor()
    {
        $this->assertEqual($this->PhpBase->_schema_name,'Housing');
        $this->assertEqual($this->PhpBase->_domain,@$_SERVER['SERVER_NAME']);
        
        $this->assertErrorPattern('/does not exist on the schemes/', new PhpBase('InvalidSchema'));
    }
    
    function test_for_setDomain()
    {
        $_SERVER['SERVER_NAME'] = 'example.com';
        $this->PhpBase->setDomain();
        $this->assertEqual($this->PhpBase->_domain, 'example.com');
        
        $this->PhpBase->setDomain('test.exaple.com');
        $this->assertEqual($this->PhpBase->_domain, 'test.exaple.com');
    }
    
    function test_of_getSchema()
    {
        include('../PhpBase/schemes/Housing.php');
        $this->assertEqual($this->PhpBase->getSchema(), $schema['_attributes']);
        
        $this->tearDown();
        $this->setUp();
        
        $this->assertEqual(strtolower(get_class($this->PhpBase->addItem($this->sample_item))), 'googlebaseitem');
        $this->assertEqual($this->PhpBase->getSchema(), $schema['_attributes']);
        

        $new_item = array_merge($this->sample_item, array('id'=>123, 'custom'=>'custom value'));
        
        $this->assertEqual(strtolower(get_class($this->PhpBase->addItem($new_item))), 'googlebaseitem');
        $modified_attributes = $schema['_attributes'];
        $modified_attributes[] = 'custom';
        $this->assertEqual($this->PhpBase->getSchema(), $modified_attributes);
        
        $this->assertEqual($this->PhpBase->getSchema(false), $schema['_attributes']);
    }
    
    function test_of_addItem()
    {
        $this->assertFalse($this->PhpBase->addItem(array()));
        
        $this->assertEqual(strtolower(get_class($this->PhpBase->addItem($this->sample_item))), 'googlebaseitem');
        $this->assertEqual($this->PhpBase->countItems(), 1);

        $this->assertFalse($this->PhpBase->addItem($this->sample_item));
        $this->assertEqual($this->PhpBase->countItems(), 1);
        
        $new_item = array_merge($this->sample_item, array('id'=>123, 'custom'=>'custom value'));
        $this->assertEqual(strtolower(get_class($this->PhpBase->addItem($new_item))), 'googlebaseitem');
        $this->assertEqual($this->PhpBase->countItems(), 2);
        
        $this->assertFalse($this->PhpBase->addItem($new_item));
        $this->assertEqual($this->PhpBase->countItems(), 2);
        
        unset($new_item['id']);
        $this->assertFalse($this->PhpBase->addItem($new_item));
        
        $new_item['id'] = 124;
        $this->assertEqual(strtolower(get_class($this->PhpBase->addItem($new_item))), 'googlebaseitem');
        
        $this->assertEqual($this->PhpBase->countItems(), 3);
        
        $this->PhpBase->setDomain('test.exaple.com');
        $new_item['id'] = 125;
        $this->assertEqual(strtolower(get_class($this->PhpBase->addItem($new_item))), 'googlebaseitem');
        
        $last_item = $this->PhpBase->lastItem();
        $this->assertEqual($last_item->id, 'test.exaple.com;125');
        
        $this->assertEqual($this->PhpBase->countItems(), 4);
        
        $new_item['id'] = 126;
        $this->PhpBase->_appendDomainToId = false;
        $this->assertEqual(strtolower(get_class($this->PhpBase->addItem($new_item))), 'googlebaseitem');
        $last_item = $this->PhpBase->lastItem();
        $this->assertEqual($last_item->id, 126);
        
        $this->assertEqual($this->PhpBase->countItems(), 5);
        
        $new_item['id'] = 127;
        $new_item['title'] = '';
        $this->assertFalse($this->PhpBase->addItem($new_item));
        $this->assertEqual($this->PhpBase->countItems(), 5);
        
    }
    
    function test_of_addItems()
    {
        foreach (range(1,10) as $id){
            $items[$id] = array_merge($this->sample_item, array('id'=>$id));
        }
        
        $this->assertTrue($this->PhpBase->addItems($items));
        $this->assertEqual($this->PhpBase->countItems(), 10);
        
        $this->assertFalse($this->PhpBase->addItems($items));
        $this->assertEqual($this->PhpBase->countItems(), 10);
        
    }
    
    
    function test_of_setItems()
    {
        foreach (range(1,10) as $id){
            $items[$id] = array_merge($this->sample_item, array('id'=>$id));
        }
        
        $this->assertTrue($this->PhpBase->setItems($items));
        $this->assertEqual($this->PhpBase->countItems(), 10);
        
        $items = array();
        foreach (range(1,5) as $id){
            $items[$id] = array_merge($this->sample_item, array('id'=>$id));
        }
        $this->assertTrue($this->PhpBase->setItems($items));
        $this->assertEqual($this->PhpBase->countItems(), 5);
    }
    
    function test_of_tabbed_render()
    {
        $items = array();
        for ($i = 0; $i < 2; $i++){
            $this->incrementTestItem();
            $items[] = $this->sample_item;
        }
        $this->assertTrue($this->PhpBase->addItems($items));
  
        $this->assertEqual(trim('title	description	link	image_link	id	expiration_date	label	price	price_type	currency	payment_accepted	listing_type	property_type	bedrooms	bathrooms	square_footage	school_district	hoa_dues	year	agent	location
Rental house in Carlet	Beautiful house with views to the horta	http://inmoeasy.com/property/9	http://inmoeasy.com/property/9/image,http://inmoeasy.com/property/9/image2	example.com;9	2006-12-20	house,rental hose,spanish rental,carlet,vacation house	500.00	negotiable	eur	Cash,Wire Transfer	for sale	Town House	4	3	400			1994	OpenHabitat	Carlet, Valencia, Spain
Rental house in Carlet	Beautiful house with views to the horta	http://inmoeasy.com/property/10	http://inmoeasy.com/property/10/image,http://inmoeasy.com/property/10/image2	example.com;10	2006-12-20	house,rental hose,spanish rental,carlet,vacation house	500.00	negotiable	eur	Cash,Wire Transfer	for sale	Town House	4	3	400			1995	OpenHabitat	Carlet, Valencia, Spain'),
    trim($this->PhpBase->render()));
    
        $this->_tab_data = $this->PhpBase->render();
        
    }
    
    
    function _test_ftp_send()
    {
        //$this->PhpBase->_ftp_server = 'phpbase.org';
        $this->PhpBase->_rendered = $this->_tab_data;
        $this->assertTrue($this->PhpBase->send('', ''));
    }
    
    
    
}


$test = new Test_of_PhpBase();
$test->run(new HtmlReporter());


?>