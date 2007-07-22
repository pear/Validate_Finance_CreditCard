--TEST--
validate_IE_licensePlate.phpt: Unit tests for licensePlate method 'Validate/IE.php'

--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/IE.php';

echo "Test Validate_IE\n";
echo "****************\n";

//test passport
$plates = array(
'ZZ-4321', //NOK
'ZZ-54321', //OK
'ZZ-7654321', //NOK
'ZV-654321', //NOK
'ZV-7258', //OK
'ZV-8192', //OK
'ZV-7654321', //NOK
'98-KY-2655', //OK
'06-D-2600', //OK
'06-DE-2600', //NOK - DE index doesn't exist
'07=KY=23233' //NOK - wrong delimiters
);
echo "\nTest License Plates\n";
foreach ($plates as $plate) {
    echo "{$plate}: ".$noYes[Validate_IE::licensePlate($plate)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test License Plates
ZZ-4321: NO
ZZ-54321: YES
ZZ-7654321: NO
ZV-654321: NO
ZV-7258: YES
ZV-8192: YES
ZV-7654321: NO
98-KY-2655: YES
06-D-2600: YES
06-DE-2600: NO
07=KY=23233: NO

