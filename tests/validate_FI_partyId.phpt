--TEST--
validate_FI_partyId.phpt: Unit tests for partyId method 'Validate/FI.php'
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

$partyIds = array('003715728600',    // OK
                  '003707375462',    // OK
                  '003707375464',    // NOK
                  '003607375462',    // NOK
                  '003707375452',    // NOK
                  '003757286XX0',    // NOK
                  '0',               // NOK 
                  '-1',              // NOK 
                  'valid'            // NOK
);

echo "\nTest partyId\n";
foreach ($partyIds as $partyId) {
    echo "{$partyId}: ".$noYes[Validate_FI::partyId($partyId)]."\n";
}

?>
--EXPECT--
Test Validate_FI
****************

Test partyId
003715728600: YES
003707375462: YES
003707375464: NO
003607375462: NO
003707375452: NO
003757286XX0: NO
0: NO
-1: NO
valid: NO
