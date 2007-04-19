--TEST--
validate_IE_phoneNumber.phpt: Unit tests for phoneNumber method 'Validate/IE.php'

--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/IE.php';

echo "Test Validate_IE\n";
echo "****************\n";

$phoneNumbers = array('+353 1 213 4567',    // OK
                      '00353 1 213 4567',   // OK
                      '00353 1 2134 5678',   // NOK - too long
                      '0505 42123',        // OK
                      '+353 505 42123',    // OK
                      '+353 505 31456789123456789', // NOK -- too long
                      '058 56789', // OK
                      '058 567899', // NOK - too long
                      '+353 86 8765432', //OK
                      '+353 86 58765432', //OK
                      '+353 82 8111111', //NOK - unrecognised prefix
                      '12',                 // NOK -- too short
                      '0',                  // NOK -- too short
                      '-1',                 // NOK -- must be kidding me.
                      'valid'               // NOK -- not even close
);

echo "\nTest phoneNumber\n";
foreach ($phoneNumbers as $phoneNumber) {
    echo "{$phoneNumber}: ".$noYes[Validate_IE::phoneNumber($phoneNumber)]."\n";
}

exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test phoneNumber
+353 1 213 4567: YES
00353 1 213 4567: YES
00353 1 2134 5678: NO
0505 42123: YES
+353 505 42123: YES
+353 505 31456789123456789: NO
058 56789: YES
058 567899: NO
+353 86 8765432: YES
+353 86 58765432: YES
+353 82 8111111: NO
12: NO
0: NO
-1: NO
valid: NO
