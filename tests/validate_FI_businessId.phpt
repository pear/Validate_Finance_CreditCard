--TEST--
validate_FI_businessId.phpt: Unit tests for businessId method 'Validate/FI.php'
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

$businessIds = array('1572860-0',    // OK
                     '0737546-2',    // OK
                     '0737546-1',    // NOK
                     '0737546-11',   // NOK
                     '0737545-2',    // NOK
                     '57286XX-0',    // NOK
                     '0',            // NOK 
                     '-1',           // NOK 
                     'valid'         // NOK
);

echo "\nTest businessId\n";
foreach ($businessIds as $businessId) {
    echo "{$businessId}: ".$noYes[Validate_FI::businessId($businessId)]."\n";
}

?>
--EXPECT--
Test Validate_FI
****************

Test businessId
1572860-0: YES
0737546-2: YES
0737546-1: NO
0737546-11: NO
0737545-2: NO
57286XX-0: NO
0: NO
-1: NO
valid: NO
