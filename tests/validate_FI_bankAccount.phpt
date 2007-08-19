--TEST--
validate_FI_bankAccount.phpt: Unit tests for bankAccount method 'Validate/FI.php'
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

$bankAccounts = array('159030-776',         // OK
                      '12345-1',            // NOK
                      '12345-12345',        // NOK
                      '123456-1',           // NOK
                      '123456-123456789',   // NOK
                      '159030-6776',        // NOK
                      '759030-776',         // NOK
                      '0',                  // NOK 
                      '-1',                 // NOK 
                      'valid'               // NOK
); 

echo "\nTest bankAccount\n";
foreach ($bankAccounts as $bankAccount) {
    echo "{$bankAccount}: ".$noYes[Validate_FI::bankAccount($bankAccount)]."\n";
}

?>
--EXPECT--
Test Validate_FI
****************

Test bankAccount
159030-776: YES
12345-1: NO
12345-12345: NO
123456-1: NO
123456-123456789: NO
159030-6776: NO
759030-776: NO
0: NO
-1: NO
valid: NO
