--TEST--
validate_Finance.phpt: Unit tests for 'Validate/Finance.php'
--FILE--
<?php
// $Id$

// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/Finance.php';

echo "Test Validate_Finance\n";
echo "*********************\n";

$ibans = array( 'CH10002300A1023502601', // OK
                      'DE60700517550000007229', // OK
                      'DE6070051755000000722', // NOK
                      'DE10002300A1023502601', // NOK
                      'PL12100500000123456789' // NOK
);

$banknoteEuros = array( 'X05108365955', // OK
                      'X00133202927', // OK
                      'U27112359308', // OK
                     'N14037977172', // OK
                     'U27112359308', // OK
                     'U27005282276', // OK
                     'M50068527754', // OK
                     'ABC', // NOK
                     'M50068524754', // NOK
                     'A50068527754' // NOK
);

echo "Test iban\n";
foreach ($ibans as $iban) {
    echo "{$iban}: ".$noYes[Validate_Finance::iban($iban)]."\n";
}

echo "Test banknoteEuro\n";
foreach ($banknoteEuros as $banknoteEuro) {
    echo "{$banknoteEuro}: ".
        $noYes[Validate_Finance::banknoteEuro($banknoteEuro)]."\n";
}
?>
--EXPECT--
Test Validate_Finance
*********************
Test iban
CH10002300A1023502601: YES
DE60700517550000007229: YES
DE6070051755000000722: NO
DE10002300A1023502601: NO
PL12100500000123456789: NO
Test banknoteEuro
X05108365955: YES
X00133202927: YES
U27112359308: YES
N14037977172: YES
U27112359308: YES
U27005282276: YES
M50068527754: YES
ABC: NO
M50068524754: NO
A50068527754: NO
