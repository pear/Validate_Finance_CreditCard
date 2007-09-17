--TEST--
validate_IN_region.phpt: Unit tests for region method 'Validate/IN.php'

--FILE--
<?php
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/IN.php')) {
    require_once dirname(__FILE__) . '/../Validate/IN.php';
} else {
    require_once 'Validate/IN.php';
}
echo "Test Validate_IN\n";
echo "****************\n";
echo "\nTest Region\n";
$regions= array(
        "AN", "AP", "AR", "AS", "BR", "CH", "CT", "DN", "DD",
        "DL", "GA", "GJ", "HR", "HP", "JK", "JH", "KA", "KL",
        "LD", "MP", "MH", "MN", "ML", "MZ", "NL", "OR", "PY",
        "PB", "RJ", "SK", "TN", "TR", "UL", "UP", "WB",
        "IE", "AM", "BA", "NC", "IS"); 
foreach ($regions as $region) {
    echo "{$region}: ".$noYes[Validate_IN::region($region)]."\n";
}
exit(0);
?>
--EXPECT--
Test Validate_IN
****************

Test Region
AN: YES
AP: YES
AR: YES
AS: YES
BR: YES
CH: YES
CT: YES
DN: YES
DD: YES
DL: YES
GA: YES
GJ: YES
HR: YES
HP: YES
JK: YES
JH: YES
KA: YES
KL: YES
LD: YES
MP: YES
MH: YES
MN: YES
ML: YES
MZ: YES
NL: YES
OR: YES
PY: YES
PB: YES
RJ: YES
SK: YES
TN: YES
TR: YES
UL: YES
UP: YES
WB: YES
IE: NO
AM: NO
BA: NO
NC: NO
IS: NO
