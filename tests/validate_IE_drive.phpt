--TEST--
validate_IE_drive.phpt: Unit tests for drive method 'Validate/IE.php'

--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/IE.php';

echo "Test Validate_IE\n";
echo "****************\n";

echo "\nTest drive\n";
$drivingLicences = array(
'123 456 789', //OK
'123 456 789 012', //NOK -- too long
'L23 456 789', //NOK -- not numeric
);
foreach ($drivingLicences as $v) {
    echo "{$v}: ".$noYes[Validate_IE::drive($v)]."\n";
}

exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test drive
123 456 789: YES
123 456 789 012: NO
L23 456 789: NO
