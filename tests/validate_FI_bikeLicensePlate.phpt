--TEST--
validate_FI_bikeLicensePlate.phpt: Unit tests for bikeLicensePlate method 'Validate/FI.php'
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/FI.php')) {
    require_once dirname(__FILE__) . '/../Validate/FI.php';
} else {
    require_once 'Validate/FI.php';
}

echo "Test Validate_FI\n";
echo "****************\n";

$bikeLicensePlates = array('AB123',     // OK
                           'ABC123',    // OK
                           'AB1',       // OK
                           'ABC12',     // OK
                           'AB1234',    // NOK
                           'A123',      // NOK
                           '123123',    // NOK
                           '1212',      // NOK
                           'abc123',    // NOK
                           '0',         // NOK 
                           '-1',        // NOK 
                           'valid',     // NOK
                           'ежд123',    // OK
                           'жд12',      // OK
                           'ABC0',      // NOK
                           'ABC01',     // NOK
                           'ABC012'     // NOK
);

echo "\nTest bikeLicensePlate\n";
foreach ($bikeLicensePlates as $bikeLicensePlate) {
    echo "{$bikeLicensePlate}: ".$noYes[Validate_FI::bikeLicensePlate($bikeLicensePlate)]."\n";
}

?>
--EXPECT--
Test Validate_FI
****************

Test bikeLicensePlate
AB123: YES
ABC123: YES
AB1: YES
ABC12: YES
AB1234: NO
A123: NO
123123: NO
1212: NO
abc123: NO
0: NO
-1: NO
valid: NO
ежд123: YES
жд12: YES
ABC0: NO
ABC01: NO
ABC012: NO
