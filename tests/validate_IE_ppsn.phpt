--TEST--
validate_IE_ppsn.phpt: Unit tests for ppsn method 'Validate/IE.php'
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/IE.php';

echo "Test Validate_IE\n";
echo "****************\n";

echo "\nTest PPSNs\n";
$ppsns = array("1234567T",  //OK
               "1234567TW", //OK
               "1234567TT", //OK
               "1234567TX", //OK
               "0234567LX", //OK
               "1234567TB", //NOK - unrecognised suffix
               "1234567"  //NOK - no letter present
               );
foreach ($ppsns as $ppsn) {
    echo "{$ppsn}: ".$noYes[Validate_IE::ppsn($ppsn)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test PPSNs
1234567T: YES
1234567TW: YES
1234567TT: YES
1234567TX: YES
0234567LX: YES
1234567TB: NO
1234567: NO
