--TEST--
validate_FI_postalCode.phpt: Unit tests for postalCode method 'Validate/FI.php'
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

$postalCodes = array('00100',       // OK
                     '0010',        // NOK
                     '001000',      // NOK
                     '99999',       // OK, it's actually the postal code for Santa Claus :)
                     '99X99',       // NOK
                     'fin-12345',   // NOK
                     'fin-1234',    // NOK
                     'FIN-12345',   // NOK
                     'FIN-1234',    // NOK
                     'fi-12345',    // NOK
                     'fi-1234',     // NOK
                     'FI-12345',    // OK
                     'FI-1234',     // NOK
                     'FI-00100',    // OK
                     'FI-123456',   // NOK
                     'FI00100',     // NOK
                     '-12345',      // NOK
                     '0',           // NOK 
                     '-1',          // NOK 
                     'valid'        // NOK
);

echo "\nTest postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_FI::postalCode($postalCode)]."\n";
}

?>
--EXPECT--
Test Validate_FI
****************

Test postalCode
00100: YES
0010: NO
001000: NO
99999: YES
99X99: NO
fin-12345: NO
fin-1234: NO
FIN-12345: NO
FIN-1234: NO
fi-12345: NO
fi-1234: NO
FI-12345: YES
FI-1234: NO
FI-00100: YES
FI-123456: NO
FI00100: NO
-12345: NO
0: NO
-1: NO
valid: NO
