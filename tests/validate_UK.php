<?php
require_once 'PHPUnit.php';
require "Validate/UK.php";

class Validate_UK_Test extends PHPUnit_TestCase
{
    var $postcodes = array(
                /* some test-cases from the original of the postcode-check */
                'BS25 1NB'  => true,
                'B5 5TF'    => true,
                '5Ty6tty'   => false,
                '3454545'   => false,
                'SW1 4RT'   => true,
                'SW1A 4RT'  => true,
                'BS45678TH' => false,
                'BF 3RT'    => false,

                /* official examples from the docs */
                'M1 1AA' => true,
                'M60 1NW' => true,
                'CR2 6XH' => true,
                'DN55 1PT'  => true,
                'W1A 1HQ' => true,
                'EC1A 1BB' => true,
                'GIR 0AA' => true,
                
                /* some variations of the official examples which make it non-compliant */
                'V1 1AA' => false,
                'M6L 1NW' => false,
                'CJ2 6XH' => false,
                'QN55 1PT'  => false,
                'W1L 1HQ' => false,
                'EC1A 1BC' => false,
                'GIR 1AA' => false,
                
                /* additional checks by David Grant (djg), these are valid */
                'CF23 7JN' => true,
                'BS8 4UD' => true,
                'GU19 5AT' => true,
                'CF11 6TA' => true,
                'SW1A 1AA' => true,
                'NW9 0EQ' => true,

                /* additional checks by David Grant (djg), these are invalid */
                'AB12C 1AA' => false,
                'B12D 3XY' => false,
                'Q1 5AT' => false,
                'BI10 4UD' => false
                );
    var $ni = Array(
                'JM 40 24 25 C' => true,
                'JM56765F'      => false,
                'JM567645T'     => false,
                'JM567645R'     => false,
                'JM567645D'     => true,
                'BG567645D'     => false
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
