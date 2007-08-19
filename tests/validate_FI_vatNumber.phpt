--TEST--
validate_FI_vatNumber.phpt: Unit tests for vatNumber method 'Validate/FI.php'
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

$vatNumbers = array('FI15728600',    // OK
                    'FI07375462',    // OK
                    'IF15728600',    // NOK
                    'FI15728602',    // NOK
                    '003715728600',  // NOK
                    'FI57286XX0',    // NOK
                    '0',             // NOK 
                    '-1',            // NOK 
                    'valid'          // NOK
);

echo "\nTest vatNumber\n";
foreach ($vatNumbers as $vatNumber) {
    echo "{$vatNumber}: ".$noYes[Validate_FI::vatNumber($vatNumber)]."\n";
}

?>
--EXPECT--
Test Validate_FI
****************

Test vatNumber
FI15728600: YES
FI07375462: YES
IF15728600: NO
FI15728602: NO
003715728600: NO
FI57286XX0: NO
0: NO
-1: NO
valid: NO
