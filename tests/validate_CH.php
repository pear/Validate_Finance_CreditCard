<?php
// $Id$

require_once 'PHPUnit.php';
require_once 'Validate/CH.php';

class Validate_CH_Test extends PHPUnit_TestCase{
    function Validate_CH_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }
    function setUp(){}
    function tearDown(){}

    function testpostcode()
    {
        $this->assertTrue(Validate_CH::postcode('9658', true));
        $this->assertFalse(Validate_CH::postcode('9654', true));
        $this->assertFalse(Validate_CH::postcode('96c4', true));
    }
    
    function testssn()
    {
        $this->assertTrue(Validate_CH::ssn('123.45.678.113'));
        $this->assertFalse(Validate_CH::ssn('123.45.876.113'));
        $this->assertFalse(Validate_CH::ssn('123-45.678.113'));
    }

    function teststudentid()
    {
        $this->assertTrue(Validate_CH::studentid('94-119-252'));
        $this->assertTrue(Validate_CH::studentid('94119252'));
        $this->assertFalse(Validate_CH::studentid('94-199-252'));
        $this->assertFalse(Validate_CH::studentid('94.119.252'));
    }
}

$s = &new PHPUnit_TestSuite("Validate_CH_Test");
$r = PHPUnit::run($s);
echo $r->toString();

?>