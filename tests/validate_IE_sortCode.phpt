--TEST--
validate_IE_sortCode.phpt: Unit tests for sortCode method of 'Validate/IE.php'

--FILE--
<?php
// Validate test script
error_reporting(0);
$noYes = array('NO', 'YES');
require_once 'Validate/IE.php';

echo "Test Validate_IE\n";
echo "****************\n";

//test bank account
$sort_codes = array(
'123456', //OK
'A23456', //NOK - not numeric
'123456789', //NOK - too many digits
'1234567', //NOK - too many digits;
'12345', //NOK - too many digits;
'000000', // NOK - known to be invalid
'999999', // NOK - known to be invalid
);
echo "\nTest sort codes\n";
foreach ($sort_codes as $sort_code) {
    echo "{$sort_code}: ".$noYes[Validate_IE::sortCode($sort_code)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test sort codes
123456: YES
A23456: NO
123456789: NO
1234567: NO
12345: NO
000000: NO
999999: NO
