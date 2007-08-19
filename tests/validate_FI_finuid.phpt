--TEST--
validate_FI_finuid.phpt: Unit tests for finuid method 'Validate/FI.php'
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/FI.php')) {
    require_once dirname(__FILE__) . '/../Validate/FI.php';
} else {
    require_once 'Validate/FI.php';
}

echo "Test Validate_FI\n";
echo "****************\n";

$finuids = array('10011187H',    // OK
                 '10011188H',    // NOK
                 '100X1187H',    // NOK
                 '0',            // NOK 
                 '-1',           // NOK 
                 'valid'         // NOK
);

echo "\nTest finuid\n";
foreach ($finuids as $finuid) {
    echo "{$finuid}: ".$noYes[Validate_FI::finuid($finuid)]."\n";
}

?>
--EXPECT--
Test Validate_FI
****************

Test finuid
10011187H: YES
10011188H: NO
100X1187H: NO
0: NO
-1: NO
valid: NO
