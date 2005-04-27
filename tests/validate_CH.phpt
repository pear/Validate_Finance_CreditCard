--TEST--
validate_CH.phpt: Unit tests for 'Validate/CH.php'
--FILE--
<?php
// $Id$

// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/CH.php';

echo "Test Validate_CH\n";
echo "****************\n";

$postalCodes = array( '9658', // OK
                     '9654', // NOK (OK if not strong)
                     '96c4' // NOK
);
    
$ssns = array( '123.45.678.113', // OK
               '123.45.876.113', // NOK
               '123-45.678.113' // NOK
);
    
$studentids = array( '94-119-252', // OK
                     '94119252', // OK
                     '94-199-252', // NOK
                     '94.119.252' // NOK
);

echo "\nTest postalCode without check against table\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_CH::postalCode($postalCode)]."\n";
}

echo "\nTest postalCode with check against table (strong)\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_CH::postalCode($postalCode, true)]."\n";
}

echo "\nTest ssn\n";
foreach ($ssns as $ssn) {
    echo "{$ssn}: ".$noYes[Validate_CH::ssn($ssn)]."\n";
}

echo "\nTest studentid\n";
foreach ($studentids as $studentid) {
    echo "{$studentid}: ".$noYes[Validate_CH::studentid($studentid)]."\n";
}

?>
--EXPECT--
Test Validate_CH
****************

Test postalCode without check against table
9658: YES
9654: YES
96c4: NO

Test postalCode with check against table (strong)
9658: YES
9654: NO
96c4: NO

Test ssn
123.45.678.113: YES
123.45.876.113: NO
123-45.678.113: NO

Test studentid
94-119-252: YES
94119252: YES
94-199-252: NO
94.119.252: NO
