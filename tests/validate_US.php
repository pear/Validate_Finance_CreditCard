<?php
require_once 'PHPUnit.php';
require 'Validate/US.php';

class Validate_US_Test extends PHPUnit_TestCase
{
    var $zipcodes = array(
                /* some test-cases from the original of the postcode-check */
                '48103'         => true,
                '48103-6565'    => true,
                '48103 6565'    => true,
                '1234'          => false,
                '3454545'       => false,

                /* this ought to be disallowed if the official zip codes have dashes */
                '481036565'     => false,

                /* zip codes can start with any digit */
                '00125' => true,
                '12368' => true,
                '22587' => true,
                '36914' => true,
                '56412' => true,
                '68795' => true,
                '71142' => true,
                '85941' => true,
                '90125' => true,

                /* must have five or nine digits */
                '9065'      => false,
                '54268-1'   => false,
                '54-2681'   => false,
                '6154166'   => false,
                '10275776'  => false,
                '10275-776' => false,
                '1235a'     => false,
                'foo'       => false,
                'QN55 1PT'  => false
                
                );
    var $phonenumbers = array(
                /* test allowed seven digit numbers */
                array('875-0987', false)    => true,
                array('875 0987', false)    => true,
                array('8750987', false)     => true,
                array('1750987', false)     => false,
                array('0750987', false)     => false,
                array('875098a', false)     => false,
                array('8dy0985', false)     => false,

                /* test allowed seven digit numbers */
                array('875-0987', true)    => false,
                array('875 0987', true)    => false,
                array('8750987', true)     => false,
                array('1750987', true)     => false,
                array('0750987', true)     => false,
                array('875098a', true)     => false,
                array('8dy0985', true)     => false,

                /* test ten digit numbers without a required area code */
                array('(467) 875-0987', false)  => true,
                array('(467)-875-0987', false)  => true,
                array('(467)875-0987', false)   => true,
                array('(467) 875 0987', false)  => true,
                array('(467)-875 0987', false)  => true,
                array('(467)875 0987', false)   => true,
                array('(467) 8750987', false)   => true,
                array('(467)-8750987', false)   => true,
                array('(467)8750987', false)    => true,
                array('267 471-0967', false)    => true,
                array('267-471-0967', false)    => true,
                array('267471-0967', false)     => true,
                array('419 285 9377', false)    => true,
                array('419-285 9377', false)    => true,
                array('419285 9377', false)     => true,
                array('419 2859377', false)     => true,
                array('267-4710967', false)     => true,
                array('4192859377', false)      => true,

                /* test ten digit numbers with a required area code */
                array('(467) 875-0987', true)  => true,
                array('(467)-875-0987', true)  => true,
                array('(467)875-0987', true)   => true,
                array('(467) 875 0987', true)  => true,
                array('(467)-875 0987', true)  => true,
                array('(467)875 0987', true)   => true,
                array('(467) 8750987', true)   => true,
                array('(467)-8750987', true)   => true,
                array('(467)8750987', true)    => true,
                array('267 471-0967', true)    => true,
                array('267-471-0967', true)    => true,
                array('267471-0967', true)     => true,
                array('419 285 9377', true)    => true,
                array('419-285 9377', true)    => true,
                array('419285 9377', true)     => true,
                array('419 2859377', true)     => true,
                array('267-4710967', true)     => true,
                array('4192859377', true)      => true,

                /* test ten digit numbers without a required area code */
                array('(167) 175-0987', false)  => false,
                array('(467) 075-0987', false)  => false,
                array('(467)-awe-0987', false)  => false,
                array('(4r4x7)-875-0987', false)=> false,
                array('(469875-0987', false)    => false,
                array('98 487-0987', false)        => false,

                /* test ten digit numbers with a required area code */
                array('(4a7) 875-0987', true)  => false,
                array('(467)-085-0987', true)  => false,
                array('(467)87-0987', true)   => false,
                array('(46e) t75 0987', true)  => false

            );

    var $states = array(
                'MT'    => true,
                'DC'    => true,
                'ILL'   => false,
                'il'    => true,
                'FLA'   => false,
                'NL'    => false
            );

    var $ssn = array(
                // Should work
                '712180565' => true,
                '019880001' => true,
                '019889999' => true,
                '133920565' => true,
                '001020030' => true,
                '097920845' => true,
                '097469490' => true,
                '001-01-3000' => true,
                '001 42 3000' => true,
                '001 44 3000' => true,
           
                // Should fail
                '001 43 3000' => false,
                '019880000' => false,
                '712194509' => false,
                '019000000' => false,
                '019890000' => false,
                '001713000' => false,
                '133939759' => false,
                '097999490' => false,
            );

    function Validate_US_Test($name)
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
            $r = Validate_US::postalCode($zipcode);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testPhonenumber()
    {
        foreach ($this->phonenumbers as $nums => $expected_result) {
            $r = Validate_US::phonenumber($nums[0], $nums[1]);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testRegion()
    {
        foreach ($this->states as $statecode => $expected_result) {
            $r = Validate_US::region($statecode);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testSSN()
    {
        foreach ($this->ssn as $ssn => $expected_result) {
            $r = Validate_US::ssn($ssn);
            $this->assertEquals($r, $expected_result);
        }
    }
}

// runs the tests
$suite = new PHPUnit_TestSuite('Validate_US_Test');
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();
?>