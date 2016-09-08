--TEST--
credit_card.phpt: Unit tests for 'Validate/Finance/CreditCard.php'
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/Finance/CreditCard.php';

$cards = array(
                    0, // NOK
                    -1, // NOK
                    '6762195515061813', // OK
                    '6762 1955 1506 1813', // OK
                    '6762195515061814', // NOK
                    '0x1A6A195515061813', // NOK
                    '4405771709596026', // OK
                    '4405771709596,26', // NOK
                    '4405771709596.26', // NOK
                    'Big brother watch', // NOK
                    '4222222222222', // OK
                    '4222222221.00'); // NOK

// Test card type detection
$cardTypes = array(
        '4111 1111 1111 1111' => 'VISA',
        '4111-1111-1111-1111' => 'VISA',
        '4111111111111111'    => 'VISA',
        '5500 0000 0000 0004' => 'MasterCard',
        '2223000048400011'    => 'MasterCard',
        '3400 0000 0000 009'  => 'AMEX',
        '3088 0000 0000 0009' => 'JCB',
        '2014 0000 0000 009'  => 'ENROUTE',
        '6011 0000 0000 0004' => 'DISCOVER',
        '3000 0000 0000 04'   => 'DINERSCLUB');

// Test CVV
$cvvs = array(
        array('1',     'visa'), // NOK
        array('23',    'visa'), // NOK
        array('111',   'visa'), // OK
        array('123',   'visa'), // OK
        array('13',    'visa'), // NOK
        array('1234',  'visa'), // NOK
        array('897',   'mastercard'), // OK
        array('123',   'jcb'), // NOK
        array('8765',  'jcb'), // NOK
        array('8765',  ''), // NOK
        array('1234',  'amex'), // OK
        array('465',   'amex'), // NOK
        array('10O7',  'amex'), // NOK
        array('abc',   'visa'), // NOK
        array('123.0', 'visa'), // NOK
        array('1.2',   'visa'), // NOK
        array('123',   'discover'), // OK
        array('4429',  'discover')); // NOK

/*    // Test valid LUHN, but invalid cards
$shortCards = array(
        'VISA'       => '41111',
        'MasterCard' => '5413',
        'AMEX'       => '3400009',
        'JCB'        => '21311',
        'ENROUTE'    => '2014009',
        'DISCOVER'   => '60110004',
        'DINERSCLUB' => '300004'); */

echo "Test Validate_Finance_CreditCard class\n";

echo "*** Test credit card number only ***\n";
foreach ($cards as $card) {
    echo "{$card}: ".
        $noYes[Validate_Finance_CreditCard::number($card)]."\n";
}

echo "*** Test credit card without type (should be OK) ***\n";
foreach ($cardTypes as $card => $type) {
    echo $card . ' : '.
    $noYes[Validate_Finance_CreditCard::number($card)]."\n";
}

echo "*** Test credit card number with type ***\n";
$cardBrands = array_reverse(array_unique($cardTypes));
foreach ($cardBrands as $brand) {
    foreach ($cardTypes as $card => $type) {
        echo 'On "' . $card . '" is ' . 
        (($type == $brand) ? '' : 'not') . ' a "' . $brand . '" : '.
        $noYes[Validate_Finance_CreditCard::type($card, $brand)]."\n";
    }
}

echo "*** Test card verification value ***\n";
foreach ($cvvs as $cvv) {
    echo "{$cvv[0]} by '{$cvv[1]}': ".
        $noYes[Validate_Finance_CreditCard::cvv($cvv[0], $cvv[1])]."\n";
}

/*    function testValidLuhn()
{
    $pass = false;
    foreach ($shortCards as $type => $number) {
        $r = Validate_Finance_CreditCard::isValid($number, $type);
        $pass |= $r;
    }
    $assertFalse($pass);
} */
?>
--EXPECT--
Test Validate_Finance_CreditCard class
*** Test credit card number only ***
0: NO
-1: NO
6762195515061813: YES
6762 1955 1506 1813: YES
6762195515061814: NO
0x1A6A195515061813: NO
4405771709596026: YES
4405771709596,26: NO
4405771709596.26: NO
Big brother watch: NO
4222222222222: YES
4222222221.00: NO
*** Test credit card without type (should be OK) ***
4111 1111 1111 1111 : YES
4111-1111-1111-1111 : YES
4111111111111111 : YES
5500 0000 0000 0004 : YES
2223000048400011 : YES
3400 0000 0000 009 : YES
3088 0000 0000 0009 : YES
2014 0000 0000 009 : YES
6011 0000 0000 0004 : YES
3000 0000 0000 04 : YES
*** Test credit card number with type ***
On "4111 1111 1111 1111" is not a "DINERSCLUB" : NO
On "4111-1111-1111-1111" is not a "DINERSCLUB" : NO
On "4111111111111111" is not a "DINERSCLUB" : NO
On "5500 0000 0000 0004" is not a "DINERSCLUB" : NO
On "2223000048400011" is not a "DINERSCLUB" : NO
On "3400 0000 0000 009" is not a "DINERSCLUB" : NO
On "3088 0000 0000 0009" is not a "DINERSCLUB" : NO
On "2014 0000 0000 009" is not a "DINERSCLUB" : NO
On "6011 0000 0000 0004" is not a "DINERSCLUB" : NO
On "3000 0000 0000 04" is  a "DINERSCLUB" : YES
On "4111 1111 1111 1111" is not a "DISCOVER" : NO
On "4111-1111-1111-1111" is not a "DISCOVER" : NO
On "4111111111111111" is not a "DISCOVER" : NO
On "5500 0000 0000 0004" is not a "DISCOVER" : NO
On "2223000048400011" is not a "DISCOVER" : NO
On "3400 0000 0000 009" is not a "DISCOVER" : NO
On "3088 0000 0000 0009" is not a "DISCOVER" : NO
On "2014 0000 0000 009" is not a "DISCOVER" : NO
On "6011 0000 0000 0004" is  a "DISCOVER" : YES
On "3000 0000 0000 04" is not a "DISCOVER" : NO
On "4111 1111 1111 1111" is not a "ENROUTE" : NO
On "4111-1111-1111-1111" is not a "ENROUTE" : NO
On "4111111111111111" is not a "ENROUTE" : NO
On "5500 0000 0000 0004" is not a "ENROUTE" : NO
On "2223000048400011" is not a "ENROUTE" : NO
On "3400 0000 0000 009" is not a "ENROUTE" : NO
On "3088 0000 0000 0009" is not a "ENROUTE" : NO
On "2014 0000 0000 009" is  a "ENROUTE" : YES
On "6011 0000 0000 0004" is not a "ENROUTE" : NO
On "3000 0000 0000 04" is not a "ENROUTE" : NO
On "4111 1111 1111 1111" is not a "JCB" : NO
On "4111-1111-1111-1111" is not a "JCB" : NO
On "4111111111111111" is not a "JCB" : NO
On "5500 0000 0000 0004" is not a "JCB" : NO
On "2223000048400011" is not a "JCB" : NO
On "3400 0000 0000 009" is not a "JCB" : NO
On "3088 0000 0000 0009" is  a "JCB" : YES
On "2014 0000 0000 009" is not a "JCB" : NO
On "6011 0000 0000 0004" is not a "JCB" : NO
On "3000 0000 0000 04" is not a "JCB" : NO
On "4111 1111 1111 1111" is not a "AMEX" : NO
On "4111-1111-1111-1111" is not a "AMEX" : NO
On "4111111111111111" is not a "AMEX" : NO
On "5500 0000 0000 0004" is not a "AMEX" : NO
On "2223000048400011" is not a "AMEX" : NO
On "3400 0000 0000 009" is  a "AMEX" : YES
On "3088 0000 0000 0009" is not a "AMEX" : NO
On "2014 0000 0000 009" is not a "AMEX" : NO
On "6011 0000 0000 0004" is not a "AMEX" : NO
On "3000 0000 0000 04" is not a "AMEX" : NO
On "4111 1111 1111 1111" is not a "MasterCard" : NO
On "4111-1111-1111-1111" is not a "MasterCard" : NO
On "4111111111111111" is not a "MasterCard" : NO
On "5500 0000 0000 0004" is  a "MasterCard" : YES
On "2223000048400011" is  a "MasterCard" : YES
On "3400 0000 0000 009" is not a "MasterCard" : NO
On "3088 0000 0000 0009" is not a "MasterCard" : NO
On "2014 0000 0000 009" is not a "MasterCard" : NO
On "6011 0000 0000 0004" is not a "MasterCard" : NO
On "3000 0000 0000 04" is not a "MasterCard" : NO
On "4111 1111 1111 1111" is  a "VISA" : YES
On "4111-1111-1111-1111" is  a "VISA" : YES
On "4111111111111111" is  a "VISA" : YES
On "5500 0000 0000 0004" is not a "VISA" : NO
On "2223000048400011" is not a "VISA" : NO
On "3400 0000 0000 009" is not a "VISA" : NO
On "3088 0000 0000 0009" is not a "VISA" : NO
On "2014 0000 0000 009" is not a "VISA" : NO
On "6011 0000 0000 0004" is not a "VISA" : NO
On "3000 0000 0000 04" is not a "VISA" : NO
*** Test card verification value ***
1 by 'visa': NO
23 by 'visa': NO
111 by 'visa': YES
123 by 'visa': YES
13 by 'visa': NO
1234 by 'visa': NO
897 by 'mastercard': YES
123 by 'jcb': NO
8765 by 'jcb': NO
8765 by '': NO
1234 by 'amex': YES
465 by 'amex': NO
10O7 by 'amex': NO
abc by 'visa': NO
123.0 by 'visa': NO
1.2 by 'visa': NO
123 by 'discover': YES
4429 by 'discover': NO
