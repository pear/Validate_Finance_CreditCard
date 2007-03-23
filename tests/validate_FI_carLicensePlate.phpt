--TEST--
validate_FI_carLicensePlate.phpt: Unit tests for carLicensePlate method 'Validate/FI.php'
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/FI.php';

echo "Test Validate_FI\n";
echo "****************\n";

$carLicensePlates = array('AB-123',     // OK
                          'ABC-123',    // OK
                          'AB-1',       // OK
                          'ABC-12',     // OK
                          'AB-1234',    // NOK
                          'A-123',      // NOK
                          '123-123',    // NOK
                          '12-12',      // NOK
                          'abc-123',    // NOK
                          'CD-1234',    // OK
                          'C-12345',    // OK
                          'CD-12',      // OK
                          'C-12',       // OK
                          'C-1',        // OK
                          'D-123',      // NOK
                          'C-123456',   // NOK
                          'CD-12345',   // NOK
                          'cd-1234',    // NOK
                          'c-12345',    // NOK
                          '0',          // NOK 
                          '-1',         // NOK 
                          'valid'       // NOK
);

echo "\nTest carLicensePlate\n";
foreach ($carLicensePlates as $carLicensePlate) {
    echo "{$carLicensePlate}: ".$noYes[Validate_FI::carLicensePlate($carLicensePlate)]."\n";
}

?>
--EXPECT--
Test Validate_FI
****************

Test carLicensePlate
AB-123: YES
ABC-123: YES
AB-1: YES
ABC-12: YES
AB-1234: NO
A-123: NO
123-123: NO
12-12: NO
abc-123: NO
CD-1234: YES
C-12345: YES
CD-12: YES
C-12: YES
C-1: YES
D-123: NO
C-123456: NO
CD-12345: NO
cd-1234: NO
c-12345: NO
0: NO
-1: NO
valid: NO
