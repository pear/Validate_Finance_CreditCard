<?php

require_once 'PHPUnit.php';
require_once 'Validate/AT.php';

class Validate_AT_Test extends PHPUnit_TestCase{
    function Validate_AT_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }
    function setUp(){}
    function tearDown(){}
    
    function testpostcode()
    {
        $this->assertTrue(Validate_AT::postcode(7033, true));
        $this->assertTrue(Validate_AT::postcode(7000, true));
        $this->assertTrue(Validate_AT::postcode(4664, true));
        $this->assertTrue(Validate_AT::postcode(2491, true));
        $this->assertFalse(Validate_AT::postcode(1000, true));
        $this->assertFalse(Validate_AT::postcode(9999, true));
        $this->assertFalse(Validate_AT::postcode('abc', true));
        $this->assertFalse(Validate_AT::postcode('a7000', true));
    }
    
    function testssn()
    {
        $this->assertTrue(Validate_AT::ssn('4298 02-12-82'));
        $this->assertTrue(Validate_AT::ssn('1508101050'));
        $this->assertFalse(Validate_AT::ssn(1508101051));
        $this->assertFalse(Validate_AT::ssn(4290021282));
        $this->assertFalse(Validate_AT::ssn('21 34 23 12 74'));
    }
}

$s = &new PHPUnit_TestSuite("Validate_AT_Test");
$r = PHPUnit::run($s);
echo $r->toString();

?>