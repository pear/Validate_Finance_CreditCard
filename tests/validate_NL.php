<?php
require_once 'PHPUnit.php';
require_once( "Validate/NL.php" );

class Validate_NL_Test extends PHPUnit_TestCase
{
    var $postcodes = array(
                    "1234 AB"   => true,
                    "1234 ab"   => true,
                    "1234AB"    => true,
                    "1234ab"    => true,
                    "1234aB"    => true,
                    "123456"    => false,
                    "1234"      => false,
                    "AB1234"    => false,
                    "aB12 34"   => false
                    );

    var $phonenumbers = array(
                    "0612345678"    => array(VALIDATE_NL_PHONENUMBER_TYPE_MOBILE, true),
                    "0031612345678" => array(VALIDATE_NL_PHONENUMBER_TYPE_MOBILE, true),
                    "+31612345678"  => array(VALIDATE_NL_PHONENUMBER_TYPE_MOBILE, true),
                    "0101234567"    => array(VALIDATE_NL_PHONENUMBER_TYPE_NORMAL, true),
                    "+31101234567"  => array(VALIDATE_NL_PHONENUMBER_TYPE_NORMAL, true),
                    "0031101234567" => array(VALIDATE_NL_PHONENUMBER_TYPE_NORMAL, true),
                    "0612345678"    => array(VALIDATE_NL_PHONENUMBER_TYPE_ANY, true),
                    "+31101234567"  => array(VALIDATE_NL_PHONENUMBER_TYPE_ANY, true),
                    "+31101234567"  => array(VALIDATE_NL_PHONENUMBER_TYPE_MOBILE, false),
                    "+0031612345678"=> array(VALIDATE_NL_PHONENUMBER_TYPE_NORMAL, false)
            );

    var $ssns = array(
                    "12345678"=> false,
                    "1234567890"=> false,
                    "123456789"=> true
                );

    var $bank_accounts = array(
                    "640000231" => true,
                    "640400231" => false
                );

    function Validate_NL_Test( $name )
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
            $r = Validate_NL::postcode($postcode);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testPhone()
    {
        foreach ($this->phonenumbers as $phonenumber=>$data){
            $r = Validate_NL::phonenumber($phonenumber, $data[0]);
            $this->assertEquals($r, $data[1]);
        }
    }

    function testSSN()
    {
        foreach ($this->ssns as $ssn=>$expected_result){
            $r = Validate_NL::SSN($ssn);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testBankAccount()
    {
        foreach ($this->bank_accounts as $account=>$expected_result){
            $r = Validate_NL::bankAccountNumber($account);
            $this->assertEquals($r, $expected_result);
        }
    }
}

// runs the tests
$suite = new PHPUnit_TestSuite("Validate_NL_Test");
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();
?>