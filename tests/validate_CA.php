<?php
require_once 'PHPUnit.php';
require 'Validate/CA.php';

class Validate_CA_Test extends PHPUnit_TestCase
{
    var $zipcodes = array(
                '48103'      => false,
                'A1B 3B4'    => true,
                '1A2 B3C'    => false,
                'a1b 3b4'    => true,
                'A2B3B4'     => true,
                'A2B-3B4'    => true,
                '10B 1C2'    => false,
                '10B1C2'     => false,
                '345 545'    => false,
                '345545'     => false,

                /* postal codes can only start with certain letters */
                'A0A 8Z8'  => true,
                'B1A 8Y8'  => true,
                'C2A 8X8'  => true,
                'D3A  8W8' => false,
                'E3A  8V8' => true,
                'F3A  8U8' => false,
                'G4A-8T8'  => true,
                'H5A 8S8'  => true,
                'I5A 8R8'  => false,
                'J6A 8Q8'  => true,
                'K7A 8P8'  => true,
                'L8A 8P8'  => true,
                'M9A 8N8'  => true,
                'N0A 8M8'  => true,
                'O1A 8L8'  => false,
                'P2A 8K8'  => true,
                'Q3A 8J8'  => false,
                'R4A 8J8'  => true,
                'S5A 8H8'  => true,
                'T6A 8G8'  => true,
                'U7A 8F8'  => false,
                'V8A 8E8'  => true,
                'W9A 8D8'  => false,
                'X0A 8C8'  => true,
                'Y1A 8B8'  => true,
                'Z2A 8A8'  => false,

                /* I and O never occur */
                'Y1I 8B8'  => false,
                'Y1B 8I8'  => false,
                'Y1O 8B8'  => false,
                'Y1B 8O8'  => false,

                /* must have six "digits" */
                '9A065'     => false,
                '9A0 6B'    => false,
                '9A0  6B'   => false,
                '9065'      => false,
                'A2B-4C'    => false,
                'B2A--4C'   => false,
                'A2B 3B45'  => false,
                'A2B 3B4C'  => false,
                'A2B3C4E'   => false,
                'ABCEFG'    => false,
                '1235a'     => false,
                'foo'       => false,
                'QN55 1PT'  => false
                );

    var $phonenumbers = array(
                /* test allowed seven digit numbers */
                array('875-0987', false, true),
                array('875 0987', false, true),
                array('8750987', false, true),
                array('1750987', false, false),
                array('0750987', false, false),
                array('875098a', false, false),
                array('8dy0985', false, false),

                /* test allowed seven digit numbers */
                array('875-0987', true, false),
                array('875 0987', true, false),
                array('8750987', true, false),
                array('1750987', true, false),
                array('0750987', true, false),
                array('875098a', true, false),
                array('8dy0985', true, false),

                /* test ten digit numbers without a required area code */
                array('(467) 875-0987', false, true),
                array('(467)-875-0987', false, true),
                array('(467)875-0987', false, true),
                array('(467) 875 0987', false, true),
                array('(467)-875 0987', false, true),
                array('(467)875 0987', false, true),
                array('(467) 8750987', false, true),
                array('(467)-8750987', false, true),
                array('(467)8750987', false, true),
                array('267 471-0967', false, true),
                array('267-471-0967', false, true),
                array('267471-0967', false, true),
                array('419 285 9377', false, true),
                array('419-285 9377', false, true),
                array('419285 9377', false, true),
                array('419 2859377', false, true),
                array('267-4710967', false, true),
                array('4192859377', false, true),

                /* test ten digit numbers with a required area code */
                array('(467) 875-0987', true, true),
                array('(467)-875-0987', true, true),
                array('(467)875-0987', true, true),
                array('(467) 875 0987', true, true),
                array('(467)-875 0987', true, true),
                array('(467)875 0987', true, true),
                array('(467) 8750987', true, true),
                array('(467)-8750987', true, true),
                array('(467)8750987', true, true),
                array('267 471-0967', true, true),
                array('267-471-0967', true, true),
                array('267471-0967', true, true),
                array('419 285 9377', true, true),
                array('419-285 9377', true, true),
                array('419285 9377', true, true),
                array('419 2859377', true, true),
                array('267-4710967', true, true),
                array('4192859377', true, true),
                array('(313) 535-8553', true, true),

                /* test ten digit numbers without a required area code */
                array('(167) 175-0987', false, false),
                array('(467) 075-0987', false, false),
                array('(467)-awe-0987', false, false),
                array('(4r4x7)-875-0987', false, false),
                array('(469875-0987', false, false),
                array('98 487-0987', false, false),

                /* test ten digit numbers with a required area code */
                array('(4a7) 875-0987', true, false),
                array('(467)-085-0987', true, false),
                array('(467)87-0987', true, false),
                array('(46e) t75 0987', true, false),
                
                // This should fail, less digit then is needed
                array('(123) 456-78', true, false),
                array('(517) 474-', true, false),

            );

    var $states = array(
                'QC'    => true,
                'SK'    => true,
                'ONT'   => false,
                'mb'    => true,
                'XY'    => false
            );

    var $ssn = array(
                // Should work
                '123456782' => true,
                '123/456/782' => true,
                '123 456 782' => true,
                '123-456-782' => true,
                '233345677' => true,

                // Should fail
                '012 345 674' => false,
                '012345674' => false,
                '312345671' => false,
                '831234562' => false,
                '934345679' => false,
                '934305673' => false,
                '9343.5673' => false,
                '9343O5673' => false,
                '23A34567B' => false
            );

    function Validate_CA_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    /* will be used later */
    function setup()
    {

    }

    function tearDown()
    {
    }

    function testPostalCode()
    {
        foreach ($this->zipcodes as $zipcode => $expected_result) {
            $r = Validate_CA::postalCode($zipcode);
            $this->assertEquals($r, $expected_result, $zipcode);
        }
    }

    function testPhoneNumber()
    {
        foreach ($this->phonenumbers as $nums) {
            $r = Validate_CA::phonenumber($nums[0], $nums[1]);
            $this->assertEquals($r, $nums[2]);
        }
    }

    function testRegion()
    {
        foreach ($this->states as $statecode => $expected_result) {
            $r = Validate_CA::region($statecode);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testSSN()
    {
        foreach ($this->ssn as $ssn => $expected_result) {
            $r = Validate_CA::ssn($ssn);
            $this->assertEquals($r, $expected_result, $ssn);
        }
    }
}

// runs the tests
$suite = new PHPUnit_TestSuite('Validate_CA_Test');
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();
?>