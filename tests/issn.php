<?php
require_once 'PHPUnit.php';
require 'Validate/ISPN.php';

class Validate_ISSN_Test extends PHPUnit_TestCase
{
    var $issn = array(
        '0366-3590' => true,
        '03663590' => true,
        '0004-6620' => true,
        '0394-6320' => true,
        '0395-7500' => true,
        '8675-4548' => true,
        '4342-7677' => true,
        '4545-6569' => true,
        '3434-6872' => true,
        
        '9685-5656' => false,
        '8768-4564' => false,
        '4564-7786' => false,
        '2317-8472' => false,
        '8675-4543' => false,
        '4342-7675' => false
    );

    function Validate_ISSN_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function testISSN()
    {
        foreach ($this->issn as $issn => $expected_result) {
            $r = Validate_ISPN::issn($issn);
            $this->assertEquals($r, $expected_result);
        }
    }
}


// runs the tests
$suite = new PHPUnit_TestSuite('Validate_ISSN_Test');
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();
?>