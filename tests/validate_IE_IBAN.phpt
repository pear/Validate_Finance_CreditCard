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
$IBANs = array(
//(test value copied from http://www.tbg5-finance.org/?ibandocs.shtml
'IE29AIBK93115212345678', //OK
'IE29AIBK93115212345679', //NOK - checksum problem
'XX29AIBK93115212345678' //NOK - invalid country code
);
$combo = array(
array('IE29AIBK93115212345678','AIBK'),
array('IE79BOFI93115212345678','AIBK'),
array('IE79BOFI93115212345678','BOFI')
);
echo "\nTest IBANs\n";
foreach ($IBANs as $IBAN) {
    echo "{$IBAN}: ".$noYes[Validate_IE::IBAN($IBAN)]."\n";
}
echo "\nTest IBANs with SWIFTs\n";
foreach ($combo as $test) {
    $iban  = $test[0];
    $swift = $test[1];
    echo "{$iban} {$swift}: ".$noYes[Validate_IE::IBAN($iban, $swift)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test IBANs
IE29AIBK93115212345678: YES
IE29AIBK93115212345679: NO
XX29AIBK93115212345678: NO

Test IBANs with SWIFTs
IE29AIBK93115212345678 AIBK: YES
IE79BOFI93115212345678 AIBK: NO
IE79BOFI93115212345678 BOFI: YES
