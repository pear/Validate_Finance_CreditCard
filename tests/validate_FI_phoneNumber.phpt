--TEST--
validate_FI_phoneNumber.phpt: Unit tests for phoneNumber method 'Validate/FI.php'
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

$phoneNumbers = array('+358 50 1234567',    // OK
                      '+358 9 1234 567',    // OK
                      '00358 9 1234 567',   // OK
                      '050 1234567',        // OK
                      '050 1234 567',       // OK
                      '040-1234567',        // OK
                      '(09) 1234567',       // OK
                      '(09) 1234 567',      // OK
                      '+358 50 123456789123456789', // NOK
                      '12',                 // NOK
                      '0',                  // NOK 
                      '-1',                 // NOK 
                      'valid'               // NOK
);

echo "\nTest phoneNumber\n";
foreach ($phoneNumbers as $phoneNumber) {
    echo "{$phoneNumber}: ".$noYes[Validate_FI::phoneNumber($phoneNumber)]."\n";
}

?>
--EXPECT--
Test Validate_FI
****************

Test phoneNumber
+358 50 1234567: YES
+358 9 1234 567: YES
00358 9 1234 567: YES
050 1234567: YES
050 1234 567: YES
040-1234567: YES
(09) 1234567: YES
(09) 1234 567: YES
+358 50 123456789123456789: NO
12: NO
0: NO
-1: NO
valid: NO
