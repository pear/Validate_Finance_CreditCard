--TEST--
validate_AR_post_code.phpt: Unit tests for postalCode method 'Validate/AR.php'

--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/AR.php')) {
    require_once dirname(__FILE__) . '/../Validate/AR.php';
} else {
    require_once 'Validate/AR.php';
}

echo "Test Validate_AR\n";
echo "****************\n";

//test passport
$codes = array(
'B1234ABC', //OK
'T2345BCD', //OK
'A1234ABC', //NOK
'U1234ABC', //NOK
'U123AB', //NOK
'U1234ABCD', //NOK
'b1234abc', //NOK
'1234', //OK
'B1234'//OK
);
echo "\nTest Postal Codes\n";
foreach ($codes as $code) {
    echo "{$code}: ".$noYes[Validate_AR::postalCode($code, false, true)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_AR
****************

Test Postal Codes
B1234ABC: YES
T2345BCD: YES
A1234ABC: NO
U1234ABC: NO
U123AB: NO
U1234ABCD: NO
b1234abc: NO
1234: YES
B1234: YES
