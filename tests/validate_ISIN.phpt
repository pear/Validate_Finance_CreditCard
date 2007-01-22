--TEST--
validate_ISIN.phpt: Unit tests for 'Validate/Finance/ISIN.php'
--FILE--
<?php
$noYes = array('NO', 'YES');
require_once 'Validate/Finance/ISIN.php';

echo "Test Validate_Finance_ISIN\n";
echo "*********************\n";

$isins = array('AT0000805668', // OK
               'de0008474008', // OK
               'LU0056994014', // OK
               'LU0056994010', // NOK
               'XX0056994010' // NOK
);

echo "Test isin\n";
foreach ($isins as $isin) {
    echo "{$isin}: ".$noYes[Validate_Finance_ISIN::validate($isin)]."\n";
}

?>
--EXPECT--
Test Validate_Finance_ISIN
*********************
Test isin
AT0000805668: YES
de0008474008: YES
LU0056994014: YES
LU0056994010: NO
XX0056994010: NO
