--TEST--
validate_IE_passport.phpt: Unit tests for passport method 'Validate/IE.php'

--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/IE.php';

echo "Test Validate_IE\n";
echo "****************\n";

//test passport
$passports = array(
'AN1234567', //OK
'BN1234567', //OK
'1N1234567', //NOK - first character should be a letter
'AN12345678' //NOK - too long
);
echo "\nTest Passports\n";
foreach ($passports as $passport) {
    echo "{$passport}: ".$noYes[Validate_IE::passport($passport)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test Passports
AN1234567: YES
BN1234567: YES
1N1234567: NO
AN12345678: NO
