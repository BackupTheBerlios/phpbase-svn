<?php

error_reporting(E_ALL);

require_once '../PhpBase/GoogleBaseItemValidator.php';

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');


class Test_for_Housing_schema_validation extends UnitTestCase
{
    function setUp()
    {
        $this->validator =& new GoogleBaseItemValidator('Housing');
    }

    function tearDown()
    {
        unset($this->validator);
    }

    function test_validate_fields()
    {
        $validator =& $this->validator;

        $this->assertFalse($validator->validate('title',''));
        $this->assertTrue($validator->validate('title',str_repeat('a',80)));
        $this->assertFalse($validator->validate('title',str_repeat('a',81)));

        $this->assertFalse($validator->validate('description',''));
        $this->assertTrue($validator->validate('description',str_repeat('a',65536)));
        $this->assertFalse($validator->validate('description',str_repeat('a',65537)));

        $this->assertFalse($validator->validate('link',''));
        $this->assertTrue($validator->validate('link','http://example.com'));
        $this->assertTrue($validator->validate('link','https://example.com'));
        $this->assertTrue($validator->validate('link','HTTP://example.com'));
        $this->assertFalse($validator->validate('link','example.com'));

        $this->assertTrue($validator->validate('image_link',''));
        $this->assertTrue($validator->validate('image_link','http://example.com'));
        $this->assertTrue($validator->validate('image_link','https://example.com'));
        $this->assertTrue($validator->validate('image_link','HTTP://example.com'));
        $this->assertFalse($validator->validate('image_link','example.com'));

        $this->assertFalse($validator->validate('id',''));
        $this->assertTrue($validator->validate('id','a'));

        $this->assertTrue($validator->validate('expiration_date',''));
        $this->assertTrue($validator->validate('expiration_date','2005-12-30'));
        $this->assertFalse($validator->validate('expiration_date','2005-30-30'));

        $this->assertTrue($validator->validate('label',''));
        $this->assertTrue($validator->validate('label','Label 1, Label 2'));
        $this->assertFalse($validator->validate('label','Label 1, '.str_repeat('a',41)));

        $this->assertTrue($validator->validate('price',''));
        $this->assertFalse($validator->validate('price',0));
        $this->assertTrue($validator->validate('price',1234));
        $this->assertFalse($validator->validate('price','123,40'));
        $this->assertTrue($validator->validate('price','123.40'));
        $this->assertTrue($validator->validate('price',123.40));

        $this->assertFalse($validator->validate('price_type',''));
        $this->assertFalse($validator->validate('price_type','not_valid'));
        $this->assertTrue($validator->validate('price_type','negotiable'));
        $this->assertTrue($validator->validate('price_type','starting'));

        $this->assertFalse($validator->validate('currency',''));
        $this->assertFalse($validator->validate('currency','US'));
        $this->assertTrue($validator->validate('currency','USD'));
        $this->assertTrue($validator->validate('currency','eur'));

        $this->assertFalse($validator->validate('payment_accepted',''));
        $this->assertTrue($validator->validate('payment_accepted','cash'));
        $this->assertTrue($validator->validate('payment_accepted','cash,Visa'));
        $this->assertFalse($validator->validate('payment_accepted','cash,money'));

        $this->assertFalse($validator->validate('listing_type',''));
        $this->assertTrue($validator->validate('listing_type','For sale'));
        $this->assertFalse($validator->validate('listing_type','renting'));

        $this->assertTrue($validator->validate('property_type',''));
        $this->assertTrue($validator->validate('property_type','whatever'));

        $this->assertTrue($validator->validate('bedrooms',''));
        $this->assertTrue($validator->validate('bedrooms',15));
        $this->assertFalse($validator->validate('bathrooms',15.50));
        $this->assertFalse($validator->validate('bedrooms','15,5'));
        $this->assertFalse($validator->validate('bedrooms','ten'));

        $this->assertTrue($validator->validate('bathrooms',''));
        $this->assertTrue($validator->validate('bathrooms',15));
        $this->assertFalse($validator->validate('bathrooms',15.50));
        $this->assertFalse($validator->validate('bathrooms','15,5'));
        $this->assertFalse($validator->validate('bathrooms','ten'));

        $this->assertTrue($validator->validate('square_footage',''));
        $this->assertTrue($validator->validate('square_footage',15));
        $this->assertTrue($validator->validate('square_footage',15.50));
        $this->assertFalse($validator->validate('square_footage','15,5'));
        $this->assertFalse($validator->validate('square_footage','ten'));

        $this->assertTrue($validator->validate('agent',''));
        $this->assertTrue($validator->validate('agent','whatever'));

        $this->assertTrue($validator->validate('year',''));
        $this->assertTrue($validator->validate('year','2005'));
        $this->assertTrue($validator->validate('year',2005));
        $this->assertFalse($validator->validate('year','05'));

        $this->assertTrue($validator->validate('location',''));
        $this->assertTrue($validator->validate('location','Carlet, Valencia'));
    }

    function test_cast_fields()
    {
        $validator =& $this->validator;

        $original = "\nShould <b>not</b> be modified\t";
        $validator->cast('title',$original);
        $this->assertEqual($original, 'Should not be modified');

        $validator->cast('description',$original);
        $this->assertEqual($original, 'Should not be modified');

        $validator->cast('link',$original);
        $this->assertEqual($original, 'Should not be modified');

        $validator->cast('price_type',$original);
        $this->assertEqual($original, 'Should not be modified');

        $validator->cast('property_type',$original);
        $this->assertEqual($original, 'Should not be modified');

        $validator->cast('square_footage',$original);
        $this->assertEqual($original, 'Should not be modified');

        $validator->cast('agent',$original);
        $this->assertEqual($original, 'Should not be modified');

        $validator->cast('location',$original);
        $this->assertEqual($original, 'Should not be modified');

        $original = strtotime('2007-10-23');
        $validator->cast('expiration_date',$original);
        $this->assertEqual($original, '2007-10-23');

        $original = explode(',','Housing,Property Spain, Rentals');
        $validator->cast('label',$original);
        $this->assertEqual($original, 'Housing,Property Spain,Rentals');

        $original = '123';
        $validator->cast('price',$original);
        $this->assertEqual($original, 123.00);

        $original = 'not a number';
        $validator->cast('price',$original);
        $this->assertEqual($original, '');

        $original = 'eur';
        $validator->cast('currency',$original);
        $this->assertEqual($original, 'eur');

        $original = '';
        $validator->cast('currency',$original);
        $this->assertEqual($original, 'usd');


        $original = explode(',','Cash , Visa,wire transfer ');
        $validator->cast('payment_accepted',$original);
        $this->assertEqual($original, 'Cash,Visa,Wire Transfer');
        $original = '';
        $validator->cast('payment_accepted',$original);
        $this->assertEqual($original, 'Cash');

        $original = '';
        $validator->cast('listing_type',$original);
        $this->assertEqual($original, 'for sale');

        $original = 'rental';
        $validator->cast('listing_type',$original);
        $this->assertEqual($original, 'rental');

        $original = '5';
        $validator->cast('bedrooms',$original);
        $this->assertEqual($original, 5);
        $original = 1.6;
        $validator->cast('bedrooms',$original);
        $this->assertEqual($original, 1);
        $original = 0;
        $validator->cast('bedrooms',$original);
        $this->assertEqual($original, '');

        $original = '5';
        $validator->cast('bathrooms',$original);
        $this->assertEqual($original, 5);
        $original = 1.6;
        $validator->cast('bathrooms',$original);
        $this->assertEqual($original, 1);
        $original = 0;
        $validator->cast('bathrooms',$original);
        $this->assertEqual($original, '');

        $original = mktime(0,0,0,1,1,2007);
        $validator->cast('year',$original);
        $this->assertEqual($original, '2007');

        $original = '2007';
        $validator->cast('year',$original);
        $this->assertEqual($original, 2007);

        $original = 2007;
        $validator->cast('year',$original);
        $this->assertEqual($original, 2007);
    }


    function test_suggest_fields()
    {
        $validator =& $this->validator;

        $this->assertEqual($validator->suggest('title',''),'');
        $this->assertEqual($validator->suggest('title',str_repeat('a',80)), str_repeat('a',80));
        $this->assertEqual($validator->suggest('title',str_repeat('a',81)), str_repeat('a',77).'...');

        $this->assertEqual($validator->suggest('description',''),'');
        $this->assertEqual($validator->suggest('description',str_repeat('a',65536)), str_repeat('a',65536));
        $this->assertEqual($validator->suggest('description',str_repeat('a',65537)), str_repeat('a',65533).'...');

        $this->assertEqual($validator->suggest('link',''),'');
        $this->assertEqual($validator->suggest('link','http://example.com'),'http://example.com');
        $this->assertEqual($validator->suggest('link','example.com'),'http://example.com');
        $this->assertFalse($validator->suggest('link','/example.com'));

        $this->assertEqual($validator->suggest('image_link',''),'');
        $this->assertEqual($validator->suggest('image_link','http://example.com'),'http://example.com');
        $this->assertEqual($validator->suggest('image_link','example.com'),'http://example.com');
        $this->assertFalse($validator->suggest('image_link','/example.com'));
        $this->assertEqual($validator->suggest('image_link',
        'http://example.com/img.jpg,http://example.com/img.jpg'),
        'http://example.com/img.jpg');
        $this->assertEqual($validator->suggest('image_link',
        'http://example.com/img.jpg,http://example.com/img2.jpg'),
        'http://example.com/img.jpg,http://example.com/img2.jpg');
        $this->assertEqual($validator->suggest('image_link',
        'https://example.com/img.jpg,www.example.com/img2.jpg'),
        'https://example.com/img.jpg,http://www.example.com/img2.jpg');
        $this->assertEqual($validator->suggest('image_link',
        'https://example.com/img.jpg,/img2.jpg'),
        'https://example.com/img.jpg');

        $this->assertEqual($validator->suggest('id','a'),'a');

        $this->assertEqual($validator->suggest('expiration_date','2005/12/30'), '2005-12-30');
        $this->assertEqual($validator->suggest('expiration_date',strtotime('2007-10-23')), '2007-10-23');
        $this->assertEqual($validator->suggest('expiration_date','next year'), date('Y-m-d',time()+(365*60*60*24)));
        $this->assertEqual($validator->suggest('expiration_date','16th June 1978'), '1978-06-16');


        $this->assertEqual($validator->suggest('label',''),'');
        $this->assertEqual($validator->suggest('label',array('Label 1','Label 2')),'Label 1,Label 2');
        $this->assertEqual($validator->suggest('label',array('Label 1','Label 2',str_repeat('a',41))),'Label 1,Label 2');

        $this->assertEqual($validator->suggest('price',''),'');
        $this->assertEqual($validator->suggest('price',0),'');
        $this->assertEqual($validator->suggest('price','0 €'),'');
        $this->assertEqual($validator->suggest('price',1234),1234.00);
        $this->assertEqual($validator->suggest('price','1234'),1234.00);
        $this->assertEqual($validator->suggest('price','123,4'),1234.00);

        $this->assertEqual($validator->suggest('price_type',''),'starting');
        $this->assertEqual($validator->suggest('price_type','starting'),'starting');
        $this->assertEqual($validator->suggest('price_type','not_valid'),'starting');
        $this->assertEqual($validator->suggest('price_type','negotiable'),'negotiable');

        $this->assertEqual($validator->suggest('currency',''),'usd');
        $this->assertEqual($validator->suggest('currency','$'),'usd');
        $this->assertEqual($validator->suggest('currency','€'),'eur');
        $this->assertEqual($validator->suggest('currency','£'),'gbp');
        $this->assertEqual($validator->suggest('currency','¥'),'jpy');
        $this->assertEqual($validator->suggest('currency','eur'),'eur');

        $this->assertEqual($validator->suggest('payment_accepted',''),'Cash');
        // not casted so returns lowercased value
        $this->assertEqual($validator->suggest('payment_accepted','cash'),'cash');
        $this->assertEqual($validator->suggest('payment_accepted',array('cash')),'Cash');
        $this->assertEqual($validator->suggest('payment_accepted',array('cash','check')),'Cash,Check');
        $this->assertEqual($validator->suggest('payment_accepted',array('cash','check','maestro')),'Cash,Check');

        $this->assertEqual($validator->suggest('listing_type',''),'for sale');
        $this->assertEqual($validator->suggest('listing_type','sale'),'for sale');
        $this->assertEqual($validator->suggest('listing_type','rental'),'rental');
        $this->assertEqual($validator->suggest('listing_type','rent'),'rental');
        $this->assertEqual($validator->suggest('listing_type','renting'),'rental');
        $this->assertEqual($validator->suggest('listing_type','sublet'),'sublet');
        $this->assertEqual($validator->suggest('listing_type','vacation rental'),'vacation rental');


        $this->assertEqual($validator->suggest('bedrooms',''),'');
        $this->assertEqual($validator->suggest('bedrooms',0),'');
        $this->assertEqual($validator->suggest('bedrooms','2'),2);
        $this->assertEqual($validator->suggest('bedrooms','2, possible spliting in 3'),2);
        $this->assertEqual($validator->suggest('bedrooms',2.8),2);

        $this->assertEqual($validator->suggest('bathrooms',''),'');
        $this->assertEqual($validator->suggest('bathrooms',0),'');
        $this->assertEqual($validator->suggest('bathrooms','2'),2);
        $this->assertEqual($validator->suggest('bathrooms','2 (1 in suite)'),2);
        $this->assertEqual($validator->suggest('bathrooms',2.8),2);

        $this->assertEqual($validator->suggest('square_footage',''),'');
        $this->assertEqual($validator->suggest('square_footage',0),'');
        $this->assertEqual($validator->suggest('square_footage',420),420);
        $this->assertEqual($validator->suggest('square_footage',42.0),42);

        $this->assertEqual($validator->suggest('agent','Same Agent'), 'Same Agent');

        $this->assertEqual($validator->suggest('year',''), '');
        $this->assertEqual($validator->suggest('year','0'), '');
        $this->assertEqual($validator->suggest('year','90'), 90);
        $this->assertEqual($validator->suggest('year','1978'), 1978);

        /**
         * @todo Location validation
         */
        $this->assertEqual($validator->suggest('location','Carlet, Valencia'), 'Carlet, Valencia');
    }


}

$test = &new Test_for_Housing_schema_validation();
$test->run(new TextReporter());




?>