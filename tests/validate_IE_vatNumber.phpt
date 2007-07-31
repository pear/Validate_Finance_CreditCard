--TEST--
validate_IE_vatNumber.phpt: Unit tests for vatNumber method 'Validate/IE.php'
--FILE--
<?php
$noYes = array('NO', 'YES');
require_once 'Validate/IE.php';

echo "Test Validate_IE\n";
echo "****************\n";

$vatNumbers = array('IE4531617P',    // OK
                    'IE8234895A',    // OK
                    'IE4736919B',    // OK
                    'IE6344246G',    // OK
                    "IE5439382J",    // OK
                    "IE6564239M",    // OK
                    "IE6572557W",    // OK
                    "IE8E89142O",    // OK
                    "IE9E61585W",    // OK
                    'IEF852100V',    // NOK - letter in wrong location
                    'IE8F5210V0',    // NOK - letter in wrong location
                    'IRL8F52100V',   // NOK - too long
);

echo "\nTest vatNumber\n";
foreach ($vatNumbers as $vatNumber) {
    echo "{$vatNumber}: ".$noYes[Validate_IE::vatNumber($vatNumber)]."\n";
}

?>
--EXPECT--
Test Validate_IE
****************

Test vatNumber
IE4531617P: YES
IE8234895A: YES
IE4736919B: YES
IE6344246G: YES
IE5439382J: YES
IE6564239M: YES
IE6572557W: YES
IE8E89142O: YES
IE9E61585W: YES
IEF852100V: NO
IE8F5210V0: NO
IRL8F52100V: NO

