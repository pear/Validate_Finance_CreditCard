<?php
require_once 'PHPUnit.php';
require_once 'Validate/Finance/CreditCard.php';

class Validate_Finance_CreditCard_Test extends PHPUnit_TestCase
{
    var $cards = array(
                    0   => false,
                    -1  => false,
                    '6762195515061813'    => true,
                    '6762 1955 1506 1813' => true,
                    '6762195515061814'    => false,
                    '0x1A6A195515061813'  => false,
                    '4405771709596026'    => true,
                    'Big brother watch'   => false
                );

    // Test card type detection
    var $cardTypes = array(
        '4111 1111 1111 1111' => 'VISA',
        '4111-1111-1111-1111' => 'VISA',
        '4111111111111111'    => 'VISA',
        '5500 0000 0000 0004' => 'MasterCard',
        '3400 0000 0000 009'  => 'AMEX',
        '3088 0000 0000 0009' => 'JCB',
        '2014 0000 0000 009'  => 'ENROUTE',
        '6011 0000 0000 0004' => 'DISCOVER',
        '3000 0000 0000 04'   => 'DINERSCLUB');

    // Test valid LUHN, but invalid cards
    var $shortCards = array(
        'VISA'       => '41111',
        'MasterCard' => '5413',
        'AMEX'       => '3400009',
        'JCB'        => '21311',
        'ENROUTE'    => '2014009',
        'DISCOVER'   => '60110004',
        'DINERSCLUB' => '300004');

    function Validate_Finance_CreditCard_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function testCreditCardNumberOnly()
    {
        $pass = true;
        $msg = '';
        foreach ($this->cards as $card => $expected_result) {
            if ($expected_result !== Validate_Finance_CreditCard::isValid($card)) {
                $msg = ' on "' . $card . '" ' . (($expected_result) ? 'valid,' : 'invalid,');
                $pass = false;
                break;
            }
        }
        $this->assertTrue($pass, $msg);
    }

    function testCreditCardTypeOnly()
    {
        $pass = true;
        $msg = '';
        $cardBrands = array_reverse(array_unique($this->cardTypes));
        foreach ($cardBrands as $brand) {
            foreach ($this->cardTypes as $card => $type) {
                $expected_result = (bool) ($type === $brand);
                if ($expected_result !== Validate_Finance_CreditCard::isType($card, $brand)) {
                    $msg = ' on "' . $card . '" is ' . (($type == $brand) ? '' : 'not') . ' a "' . $brand . '",';
                    $pass = false;
                    break;
                }
            }
        }
        $this->assertTrue($pass, $msg);
    }

    function testCreditCardNumberWithType()
    {
        $pass = true;
        $msg = '';
        $cardBrands = array_reverse(array_unique($this->cardTypes));
        foreach ($cardBrands as $brand) {
            foreach ($this->cardTypes as $card => $type) {
                $expected_result = (bool) ($type === $brand);
                if ($expected_result !== Validate_Finance_CreditCard::isValid($card, $brand)) {
                    $msg = ' on "' . $card . '" is ' . (($type == $brand) ? '' : 'not') . ' a valid "' . $brand . '",';
                    $pass = false;
                    break;
                }
            }
        }
        $this->assertTrue($pass, $msg);
    }

/*    function testValidLuhn()
    {
        $pass = false;
        foreach ($this->shortCards as $type => $number) {
            $r = Validate_Finance_CreditCard::isValid($number, $type);
            $pass |= $r;
        }
        $this->assertFalse($pass);
    } */

}

// runs the tests
$suite = new PHPUnit_TestSuite('Validate_Finance_CreditCard_Test');
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();

?>