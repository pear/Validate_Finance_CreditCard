--TEST--
validate_AT.phpt: Unit tests for 'Validate/AT.php'
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/AT.php';

echo "Test Validate_AT\n";
echo "****************\n";

$postalCodes = array( 7033, // OK
                     7000, // OK
                     4664, // OK
                     2491, // OK
                     1000, // NOK (OK if not strong)
                     9999, // NOK (OK if not strong)
                     'abc', // NOK
                     'a7000' // NOK
);
    
$ssns = array( '4298 02-12-82', // OK
               '1508101050', // OK
                1508101051, // NOK
                4290021282, // NOK
                '21 34 23 12 74' // NOK
);

echo "\nTest postalCode without check against table\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_AT::postalCode($postalCode)]."\n";
}

echo "\nTest postalCode with check against table (strong)\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_AT::postalCode($postalCode, true)]."\n";
}

echo "\nTest ssn\n";
foreach ($ssns as $ssn) {
    echo "{$ssn}: ".$noYes[Validate_AT::ssn($ssn)]."\n";
}
?>
--EXPECT--
Test Validate_AT
****************

Test postalCode without check against table
7033: YES
7000: YES
4664: YES
2491: YES
1000: YES
9999: YES
abc: NO
a7000: NO

Test postalCode with check against table (strong)
7033: YES
7000: YES
4664: YES
2491: YES
1000: NO
9999: NO
abc: NO
a7000: NO

Test ssn
4298 02-12-82: YES
1508101050: YES
1508101051: NO
4290021282: NO
21 34 23 12 74: NO
