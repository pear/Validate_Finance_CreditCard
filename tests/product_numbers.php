<?php
require_once 'PHPUnit.php';
require 'Validate/ISPN.php';

class Validate_Trade_Test extends PHPUnit_TestCase
{
    var $ucc12 = array(
        '614141210220' => true,
        '614141210221' => false
    );

    var $ean8 = array(
        '43210121' => true,
        '43210128' => false
    );

    var $ean13 = array(
        '1014541210223' => true,
        '1014541210228' => false
    );

    var $ean14 = array(
        '91014541210226' => true,
        '91014541210221' => false
    );

    var $sscc = array(
        '106141411928374657' => true,
        '106141411928374651' => false
    );

    function Validate_Trade_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function testUCC12()
    {
        foreach ($this->ucc12 as $ucc12 => $expected_result) {
            $r = Validate_ISPN::ucc12($ucc12);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testEAN8()
    {
        foreach ($this->ean8 as $ean8 => $expected_result) {
            $r = Validate_ISPN::ean8($ean8);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testEAN13()
    {
        foreach ($this->ean13 as $ean13 => $expected_result) {
            $r = Validate_ISPN::ean13($ean13);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testEAN14()
    {
        foreach ($this->ean14 as $ean14 => $expected_result) {
            $r = Validate_ISPN::ean14($ean14);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testSSCC()
    {
        foreach ($this->sscc as $sscc => $expected_result) {
            $r = Validate_ISPN::sscc($sscc);
            $this->assertEquals($r, $expected_result);
        }
    }

}


// runs the tests
$suite = new PHPUnit_TestSuite('Validate_Trade_Test');
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();
?>