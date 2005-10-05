<?php
// 
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/AU.php';

echo "Test Validate_AU\n";
echo "****************\n";

$postalCodes = array( 7033, // OK
                     7000, // OK
                     4664, // OK
                     2491, // OK
                     1000, // NOK (OK if not strong)
                     9999, // NOK (OK if not strong)
                     'abc', // NOK
                     'a7000' // NOK
);
    
$abns = array( 
'28 043 145 470',
'65 497 794 289',
'46 527 394 509',
'99 070 045 359',
'98 860 905 153',
'53 106 288 699',
'51 008 129 511',
'43 500 713 236',
'72 342 387 170',
'21 188 299 895',
'55 914 901 347',
'92 638 328 368'
 );//OK 
$acns = array( '39 585 372 949' ); //OK

echo "\nTest postalCode without check against table\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_AU::postalCode($postalCode)]."\n";
}

echo "\nTest postalCode with check against table (strong)\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_AU::postalCode($postalCode, true)]."\n";
}

echo "\nTest abn\n";
foreach ($abns as $abn) {
    echo "{$abn}: ".$noYes[Validate_AU::abn($abn)]."\n";
}

echo "\nTest acn\n";
foreach ($acns as $acn) {
    echo "{$acn}: ".$noYes[Validate_AU::acn($acn)]."\n";
}
?>
