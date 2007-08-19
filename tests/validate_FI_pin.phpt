--TEST--
validate_FI_pin.phpt: Unit tests for pin method 'Validate/FI.php'
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

$pins = array('010101-123N',    // OK
              '010101-123J',    // NOK
              '310201-123U',    // NOK, date is not valid
              'ABC123',         // NOK
              '123456789',      // NOK
              'abcdef-123N',    // NOK
              '0',              // NOK 
              '-1',             // NOK 
              'valid'           // NOK
);

echo "\nTest pin\n";
foreach ($pins as $pin) {
    echo "{$pin}: ".$noYes[Validate_FI::pin($pin)]."\n";
}

echo "\nTest pin with optional param\n";
foreach ($pins as $pin) {
    if($test = Validate_FI::pin($pin, true)) {
        echo "{$pin}: YES\n";
        print_r($test);
    } else {
        echo "{$pin}: NO\n";
    }
}

?>
--EXPECT--
Test Validate_FI
****************

Test pin
010101-123N: YES
010101-123J: NO
310201-123U: NO
ABC123: NO
123456789: NO
abcdef-123N: NO
0: NO
-1: NO
valid: NO

Test pin with optional param
010101-123N: YES
Array
(
    [0] => Male
    [1] => 1901-01-01
)
010101-123J: NO
310201-123U: NO
ABC123: NO
123456789: NO
abcdef-123N: NO
0: NO
-1: NO
valid: NO
