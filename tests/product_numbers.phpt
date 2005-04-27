--TEST--
product_numbers.phpt: Unit tests for 
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');

require 'Validate/ISPN.php';

echo "Test Validate_ISPN\n";
echo "******************\n";

$ucc12s = array(
        '614141210220', // OK
        '614141210221'); // NOK

$ean8s = array(
        '43210121', // OK
        '43210128'); // NOK

$ean13s = array(
        '1014541210223', // OK
        '1014541210228'); // NOK

$ean14s = array(
        '91014541210226', // OK
        '91014541210221'); // NOK

$ssccs = array(
        '106141411928374657', // OK
        '106141411928374651'); // NOK

$issns = array(
        '0366-3590', // OK
        '03663590', // OK
        '0004-6620', // OK
        '0394-6320', // OK
        '0395-7500', // OK
        '8675-4548', // OK
        '4342-7677', // OK
        '4545-6569', // OK
        '3434-6872', // OK
        
        '9685-5656', // NOK
        '8768-4564', // NOK
        '4564-7786', // NOK
        '2317-8472', // NOK
        '8675-4543', // NOK
        '4342-7675'); // NOK

echo "\nTest UCC12\n"; 
foreach ($ucc12s as $ucc12) {
    echo "{$ucc12} : ".$noYes[Validate_ISPN::ucc12($ucc12)]."\n";
}

echo "\nTest EAN8\n"; 
foreach ($ean8s as $ean8) {
    echo "{$ean8} : ".$noYes[Validate_ISPN::ean8($ean8)]."\n";
}

echo "\nTest EAN13\n"; 
foreach ($ean13s as $ean13) {
    echo "{$ean13} : ".$noYes[Validate_ISPN::ean13($ean13)]."\n";
}

echo "\nTest EAN14\n"; 
foreach ($ean14s as $ean14) {
    echo "{$ean14} : ".$noYes[Validate_ISPN::ean14($ean14)]."\n";
}

echo "\nTest SSCC\n"; 
foreach ($ssccs as $sscc) {
    echo "{$sscc} : ".$noYes[Validate_ISPN::sscc($sscc)]."\n";
}

echo "\nTest ISSN\n"; 
foreach ($issns as $issn) {
    echo "{$issn} : ".$noYes[Validate_ISPN::issn($issn)]."\n";
}
?>
--EXPECT--
Test Validate_ISPN
******************

Test UCC12
614141210220 : YES
614141210221 : NO

Test EAN8
43210121 : YES
43210128 : NO

Test EAN13
1014541210223 : YES
1014541210228 : NO

Test EAN14
91014541210226 : YES
91014541210221 : NO

Test SSCC
106141411928374657 : YES
106141411928374651 : NO

Test ISSN
0366-3590 : YES
03663590 : YES
0004-6620 : YES
0394-6320 : YES
0395-7500 : YES
8675-4548 : YES
4342-7677 : YES
4545-6569 : YES
3434-6872 : YES
9685-5656 : NO
8768-4564 : NO
4564-7786 : NO
2317-8472 : NO
8675-4543 : NO
4342-7675 : NO
