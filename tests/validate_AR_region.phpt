--TEST--
validate_AR_post_code.phpt: Unit tests for region method 'Validate/AR.php'

--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/AR.php')) {
    require_once dirname(__FILE__) . '/../Validate/AR.php';
} else {
    require_once 'Validate/AR.php';
}

echo "Test Validate_AR\n";
echo "****************\n";

//test passport
$codes = array(
'BA',
'CC',
'CT',
'CH',
'DF',
'CB',
'CN',
'ER',
'FM',
'JY',
'LP',
'LR',
'MZ',
'MN',
'NQ',
'RN',
'SA',
'SJ',
'SL',
'SC',
'SF',
'SE',
'TF',
'TM',
'AA', //NOK 
'ZA', //NOK 
'EG' //NOK 
);
echo "\nTest Region Code\n";
foreach ($codes as $code) {
    echo "{$code}: ".$noYes[Validate_AR::region($code)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_AR
****************

Test Region Code
BA: YES
CC: YES
CT: YES
CH: YES
DF: YES
CB: YES
CN: YES
ER: YES
FM: YES
JY: YES
LP: YES
LR: YES
MZ: YES
MN: YES
NQ: YES
RN: YES
SA: YES
SJ: YES
SL: YES
SC: YES
SF: YES
SE: YES
TF: YES
TM: YES
AA: NO
ZA: NO
EG: NO
