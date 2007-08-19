--TEST--
validate_FI_creditCard.phpt: Unit tests for creditCard method 'Validate/FI.php'
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

$creditCards = array('5427 0073 1297 6425',    // OK
                     '5427007312976425',       // OK
                     '4929 9474 1842 2442',    // OK
                     '4929947418422442',       // OK
                     '346 2488 5493 9558',     // OK
                     '346248854939558',        // OK
                     '30 2942 7659 2881',      // OK 
                     '30294276592881',         // OK 
                     '6762195515061814',       // NOK 
                     '0x1A6A195515061813',     // NOK 
                     '0',                      // NOK 
                     '-1',                     // NOK 
                     'valid'                   // NOK 
);

echo "\nTest creditCard\n";
foreach ($creditCards as $creditCard) {
    echo "{$creditCard}: ".$noYes[Validate_FI::creditCard($creditCard)]."\n";
}
?>
--EXPECT--
Test Validate_FI
****************

Test creditCard
5427 0073 1297 6425: YES
5427007312976425: YES
4929 9474 1842 2442: YES
4929947418422442: YES
346 2488 5493 9558: YES
346248854939558: YES
30 2942 7659 2881: YES
30294276592881: YES
6762195515061814: NO
0x1A6A195515061813: NO
0: NO
-1: NO
valid: NO
