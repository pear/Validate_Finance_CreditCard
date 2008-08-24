--TEST--
bug_14535.phpt: Unit tests for bug 14535; last digit of UK SSN doesn't allow a space
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/UK.php')) {
    require_once dirname(__FILE__) . '/../Validate/UK.php';
} else {
    require_once 'Validate/UK.php';
}

echo "Test Bug 14535\n";
echo "****************\n";

$ssns = array(
                'JM 40 24 25 C', // OK
                'JM56765F', // NOK
                'JM567645T', // NOK
                'JM567645R', // NOK
                'JM567645D', // OK
                'BG567645D', //NOK
                'NA123456 ', //OK
                ); 

echo "Test ssn\n";
foreach ($ssns as $ssn) {
    echo "{$ssn}: ".$noYes[Validate_UK::ssn($ssn)]."\n";
}

?>
--EXPECT--
Test Bug 14535
****************
Test ssn
JM 40 24 25 C: YES
JM56765F: NO
JM567645T: NO
JM567645R: NO
JM567645D: YES
BG567645D: NO
NA123456 : YES
