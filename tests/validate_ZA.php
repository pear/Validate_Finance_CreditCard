<?php
// $Id$

require_once 'PHPUnit.php';
require_once 'Validate/ZA.php';

class Validate_ZA_Test extends PHPUnit_TestCase{
    function Validate_ZA_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }
    function setUp(){}
    function tearDown(){}

    function testpostalCode()
    {
        $this->assertTrue(Validate_ZA::postalCode('8001', true));
        $this->assertTrue(Validate_ZA::postalCode('0001', true));
        $this->assertFalse(Validate_ZA::postalCode('9999', true));
        $this->assertFalse(Validate_ZA::postalCode('7991', true));
        $this->assertFalse(Validate_ZA::postalCode('0000', true));
    }
}

$s = &new PHPUnit_TestSuite('Validate_ZA_Test');
$r = PHPUnit::run($s);
echo $r->toString();
?>
