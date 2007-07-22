--TEST--
validate_IE_bankAC.phpt: Unit tests for bankAC method 'Validate/IE.php'

--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/IE.php';

echo "Test Validate_IE\n";
echo "****************\n";

//test bank account
$ACs        = array(
'12345678901234', //OK
'A2345678901234', //NOK - not numeric
'123456789012345', //NOK - too long
'12C4567890123'  //NOK - too short
);
$sort_codes = array(
'12345678', //OK
'A2345678', //NOK - not numeric
'123456789', //NOK - too many digits
'1234567', //NOK - too few digits;
);
echo "\nTest bank accounts\n";
foreach ($ACs as $AC) {
    echo "{$AC}: ".$noYes[Validate_IE::bankAC($AC)]."\n";
}
echo "\nTest sort codes\n";
foreach ($sort_codes as $sort_code) {
    echo "{$sort_code}: ".$noYes[Validate_IE::bankAC($sort_code, true)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test bank accounts
12345678901234: YES
A2345678901234: NO
123456789012345: NO
12C4567890123: NO

Test sort codes
12345678: YES
A2345678: NO
123456789: NO
1234567: NO
