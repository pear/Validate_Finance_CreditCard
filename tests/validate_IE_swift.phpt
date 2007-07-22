--TEST--
validate_IE_IBAN.phpt: Unit tests for IBAN method 'Validate/IE.php'

--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/IE.php';

echo "Test Validate_IE\n";
echo "****************\n";

//test bank account
$SWIFTs = array(
'AIBKIE2D', //OK
'CITIIE2X', //OK
'CITI1E2X', //NOK - typo in country code.
'CITIXX2X', //NOK - wrong country code.
'AIBKIE2DE' //NOK - too long
);
echo "\nTest SWIFTs\n";
foreach ($SWIFTs as $SWIFT) {
    echo "{$SWIFT}: ".$noYes[Validate_IE::swift($SWIFT)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test SWIFTs
AIBKIE2D: YES
CITIIE2X: YES
CITI1E2X: NO
CITIXX2X: NO
AIBKIE2DE: NO
