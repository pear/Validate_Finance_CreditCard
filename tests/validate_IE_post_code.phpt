--TEST--
validate_IE_post_code.phpt: Unit tests for postalCode method 'Validate/IE.php'

--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/IE.php';

echo "Test Validate_IE\n";
echo "****************\n";

//test passport
$codes = array(
'D1', //OK
'D6W', //OK
'D01', //NOK - shouldn't be a zero after D
'D100' //NOK - too long
);
echo "\nTest Postal Codes\n";
foreach($codes as $code) {
    echo "{$code}: ".$noYes[Validate_IE::postalCode($code)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test Postal Codes
D1: YES
D6W: YES
D01: NO
D100: NO
