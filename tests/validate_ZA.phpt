--TEST--
validate_ZA.phpt: Unit tests for 'Validate/ZA.php'
--FILE--
<?php
// $Id$

// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/ZA.php';

echo "Test Validate_ZA\n";
echo "****************\n";

$postalCodes = array(
                '8001', // OK
                '801', // NOK too short
                '0001', // OK
                '2781122', // NOK too long
                '9999', // OK
                '7991', // OK 
                '0000', // OK
                '8x01' // NOK bad alpha
);

$regions = array('WC', // OK
                 'EC', // OK
                 'NA', // NOK
                 'ZZ'); // NOK
  
echo "Test postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_ZA::postalCode($postalCode)]."\n";
}

echo "\nTest region\n";
foreach ($regions as $region) {
    echo "{$region}: ".$noYes[Validate_ZA::region($region)]."\n";
}
?>
--EXPECT--
Test Validate_ZA
****************
Test postalCode
8001: YES
801: NO
0001: YES
2781122: NO
9999: YES
7991: YES
0000: YES
8x01: NO

Test region
WC: YES
EC: YES
NA: NO
ZZ: NO
