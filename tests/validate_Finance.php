<?php
// $Id$

require_once 'PHPUnit.php';
require_once 'Validate/Finance.php';

class Validate_Finance_Test extends PHPUnit_TestCase{
    function Validate_Finance_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }
    function setUp(){}
    function tearDown(){}

    function testbanknoteEuro()
    {
        $this->assertTrue(Validate_Finance::banknoteEuro('X05108365955'));
        $this->assertTrue(Validate_Finance::banknoteEuro('X00133202927'));
        $this->assertTrue(Validate_Finance::banknoteEuro('U27112359308'));
        $this->assertTrue(Validate_Finance::banknoteEuro('N14037977172'));
        $this->assertTrue(Validate_Finance::banknoteEuro('U27112359308'));
        $this->assertTrue(Validate_Finance::banknoteEuro('U27005282276'));
        $this->assertTrue(Validate_Finance::banknoteEuro('M50068527754'));
        $this->assertFalse(Validate_Finance::banknoteEuro('ABC'));
        $this->assertFalse(Validate_Finance::banknoteEuro('M50068524754'));
        $this->assertFalse(Validate_Finance::banknoteEuro('A50068527754'));
    }
}

$s = &new PHPUnit_TestSuite("Validate_Finance_Test");
$r = PHPUnit::run($s);
echo $r->toString();

?>