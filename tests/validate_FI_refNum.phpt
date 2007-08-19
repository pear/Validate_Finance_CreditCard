--TEST--
validate_FI_refNum.phpt: Unit tests for refNum method 'Validate/FI.php'
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

$refNums = array('61 74354',         // OK
                 '6174354',          // OK
                 '123',              // NOK
                 '1234512345123451234512345',   // NOK
                 '12345 54321 123',  // NOK
                 '987 78934 83947',  // NOK
                 '345948365887635',  // NOK
                 '0',                // NOK 
                 '-1',               // NOK 
                 'valid'             // NOK
);

echo "\nTest refNum\n";
foreach ($refNums as $refNum) {
    echo "{$refNum}: ".$noYes[Validate_FI::refNum($refNum)]."\n";
}

?>
--EXPECT--
Test Validate_FI
****************

Test refNum
61 74354: YES
6174354: YES
123: NO
1234512345123451234512345: NO
12345 54321 123: YES
987 78934 83947: NO
345948365887635: NO
0: NO
-1: NO
valid: NO
