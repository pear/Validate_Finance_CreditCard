<?php
require_once 'PHPUnit.php';
require_once 'Validate/CreditCard.php';

class Validate_CreditCard_Test extends PHPUnit_TestCase
{
    var $cards = array(
                    0   => false,
                    -1  => false,
                    '6762195515061813'    => true,
                    '6762 1955 1506 1813' => true,
                    '6762195515061814'    => false,
                    '4405771709596026'    => true,
                    'Big brother is watching you' => false
                );

    // Test cards
    var $cardTypes = array(
        'VISA'       => '4111 1111 1111 1111',
        'VISA'       => '4111-1111-1111-1111',
        'VISA'       => '4111111111111111',
        'MasterCard' => '5500 0000 0000 0004',
        'AMEX'       => '3400 0000 0000 009',
        'JCB'        => '3088 0000 0000 0009',
        'ENROUTE'    => '2014 0000 0000 009',
        'DISCOVER'   => '6011 0000 0000 0004',
        'DINERSCLUB' => '3000 0000 0000 04');

    // Test valid LUHN, but invalid cards
    var $shortCards = array(
        'VISA'       => '41111',
        'MasterCard' => '5413',
        'AMEX'       => '3400009',
        'JCB'        => '21311',
        'ENROUTE'    => '2014009',
        'DISCOVER'   => '60110004',
        'DINERSCLUB' => '300004');

    function Validate_CreditCard_Test($name)
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

    function testCreditCard()
    {
        foreach ($this->cards as $card => $expected_result) {
            $r = Validate_CreditCard::creditCard($card);
            $this->assertEquals($r, $expected_result);
        }
    }

    function testDetectCreditCardType()
    {
        foreach (array_keys($this->cardTypes) as $comp_type) {
            foreach ($this->cardTypes as $type => $number) {
                $r = Validate_CreditCard::creditCardType($number, $comp_type);
                $this->assertEquals($r, (bool) ($type == $comp_type));
            }
        }
    }

    function testValidLuhnCheckBadNumberCreditCardType()
    {
        foreach ($this->shortCards as $type => $number) {
            $r = Validate_CreditCard::creditCard($number, $type);
            $this->assertEquals($r, false);
        }
    }

}

// runs the tests
$suite = new PHPUnit_TestSuite('Validate_CreditCard_Test');
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();

?>