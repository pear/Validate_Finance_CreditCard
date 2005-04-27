--TEST--
validate_DE.phpt: Unit tests for 'Validate/DE.php'
--FILE--
<?php
// $Id$

// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/DE.php';

echo "Test Validate_DE\n";
echo "****************\n";

$postalCodes = array( '10115', // OK
                      '09111', // OK
                      '80331', // OK
                     '0115', // NOK
                     '101154', // NOK
                     'x1154' // NOK
);

$bankcodes = array( '59050101', // OK
                      '60250010', // OK
                      '70051805', // OK
                      '7005180S', // NOK
                      '7005180', // NOK
                      '700518073' // NOK
);

echo "Test postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_DE::postalCode($postalCode)]."\n";
}

echo "Test bankcode\n";
foreach ($bankcodes as $bankcode) {
    echo "{$bankcode}: ".$noYes[Validate_DE::bankcode($bankcode)]."\n";
}
?>
--EXPECT--
Test Validate_DE
****************
Test postalCode
10115: YES
09111: YES
80331: YES
0115: NO
101154: NO
x1154: NO
Test bankcode
59050101: YES
60250010: YES
70051805: YES
7005180S: NO
7005180: NO
700518073: NO
