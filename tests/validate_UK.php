<?php
require_once 'PHPUnit.php';
require "Validate/UK.php";

class Validate_UK_Test extends PHPUnit_TestCase
{
    var $postcodes = array(
                'BS25 1NB'  => true,
                'B5 5TF'    => true,
                '5Ty6tty'   => false,
                '3454545'   => false,
                'SW1 4RT'   => true,
                'SW1A 4RT'  => true,
                'BS45678TH' => false,
                'BF 3RT'    => false,
                );
    var $ni = Array(
                'JM 40 24 25 C' => true,
                'JM56765F'      => false,
                'JM567645T'     => true,
                'JM567645R'     => false
            );

    var $sa = Array(
                '09-01-24'  => true,
                '345676'    => false,
                '0-78-56'   => false,
                '21-68-78'  => true,
                '34-234-56' => false
            );

    function Validate_UK_Test( $name )
    {
        $this->PHPUnit_TestCase($name);
    }

    /* will be used later */
    function setup ()
    {

    }

    function tearDown()
    {
    }

    function testPostcode()
    {
        foreach ($this->postcodes as $postcode=>$expected_result){
            $r = Validate_UK::postcode($postcode);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testNationalInsurance()
    {
        foreach ($this->ni as $ni=>$expected_result){
            $r = Validate_UK::ni($ni);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testSortCodes()
    {
        foreach ($this->sa as $sort_code=>$expected_result){
            $r = Validate_UK::sortCode($sort_code);
            $this->assertEquals($r, $expected_result);
        }
    }
}

// runs the tests
$suite = new PHPUnit_TestSuite("Validate_UK_Test");
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();
?>