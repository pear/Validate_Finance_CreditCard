--TEST--
validate_FR.phpt: Unit tests for 'Validate/FR.php'
--FILE--
<?php
// $Id$

require_once 'Validate/FR.php';

echo "Test Validate_FR\n";
echo "****************\n";

$noYes = array('NO', 'YES');

$ssns = array(  '156077851718185', // OK
                '2781120050003', // NOK too short
                '278102305000331', // OK
                '278112005000332', // NOK bad checksum
                '278112B0500033112', // NOK too long
                '278112005000331', // OK
                '278112005000339', // NOK bad checksum 
                '578112005000375', // NOK bad sex
                '278132005000363', // NOK bad month
                '278130005000321', // NOK bad dept
                '2x8112005000331'); // NOK bad alpha

$ribs = array(  '20041 01003 0175293T024 33', // OK
                '45499 91289 01697111 65', // OK
                '20041 01003 0175293T024 34', // NOK
                '45499 91289 01697111 5', // NOK
                '12345 12345 12345678901 46', // OK
                '1234 5123451 2345678901 46', // NOK
                '12345 12345 12345678901 47', // NOK
                '12345 12345 1234E67H901 46', // OK
                '12345 12345 1234E67B901 46', // NOK
                '12345 12345 VALIDATEURS 01', // OK
                '12345 12345 1234E67H901 45', // NOK
                '12345 12345 VALIDATEURS 00', // NOK
                '12345 12345 51394135492 01', // OK
                '11111 22222 33333333333 91', // OK
                '11111 22222 33333333298 02', // OK
                '11111 22222 33333333298 99'); // OK ?

$sirens = array('423068147', // OK
                '123456789', // NOK
                '123456782', // OK
                '422260208', // OK
                '12345678', // NOK
                '1234567840'); // NOK

$sirets = array('42306814700010', // OK
                '12345678912345', // NOK
                '12345678200010', // OK
                '42226020800026', // OK
                '1234567891234x25', // NOK
                '12345678', // NOK
                '1234567840'); // NOK
                
$postalCodes = array('01234', // OK
                     '012345', // NOK
                     '0123', // NOK
                     '00234', // NOK
                     '99234', // OK
                     '2A234', // NOK
                     '20234', // OK
                     '0123X'); // NOK

$regions = array('12', // OK
                 '00', // NOK
                 '1', // NOK
                 '100', // NOK
                 '20', // NOK
                 '2A', // OK
                 '2B', // OK
                 '2C', // NOK
                 '972', // OK
                 '979'); // NOK

echo "Test ssn\n";
foreach ($ssns as $ssn) {
    echo "{$ssn}: ".$noYes[Validate_FR::ssn($ssn)]."\n";
}

echo "Test rib\n";
foreach ($ribs as $rib) {
    $arib = explode(' ', $rib);
    echo "{$rib}: ".$noYes[Validate_FR::rib(array(
            'codebanque'=>$arib[0], 'codeguichet'=>$arib[1],
            'nocompte'=>$arib[2], 'key'=>$arib[3]))]."\n";
}

echo "Test siren\n";
foreach ($sirens as $siren) {
    echo "{$siren}: ".$noYes[Validate_FR::siren($siren)]."\n";
}

echo "Test siret\n";
foreach ($sirets as $siret) {
    echo "{$siret}: ".$noYes[Validate_FR::siret($siret)]."\n";
}

echo "Test postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_FR::postalCode($postalCode)]."\n";
}

echo "Test region\n";
foreach ($regions as $region) {
    echo "{$region}: ".$noYes[Validate_FR::region($region)]."\n";
}
?>
--EXPECT--
Test Validate_FR
****************
Test ssn
156077851718185: YES
2781120050003: NO
278102305000331: YES
278112005000332: NO
278112B0500033112: NO
278112005000331: YES
278112005000339: NO
578112005000375: NO
278132005000363: NO
278130005000321: NO
2x8112005000331: NO
Test rib
20041 01003 0175293T024 33: YES
45499 91289 01697111 65: YES
20041 01003 0175293T024 34: NO
45499 91289 01697111 5: NO
12345 12345 12345678901 46: YES
1234 5123451 2345678901 46: NO
12345 12345 12345678901 47: NO
12345 12345 1234E67H901 46: YES
12345 12345 1234E67B901 46: NO
12345 12345 VALIDATEURS 01: YES
12345 12345 1234E67H901 45: NO
12345 12345 VALIDATEURS 00: NO
12345 12345 51394135492 01: YES
11111 22222 33333333333 91: YES
11111 22222 33333333298 02: YES
11111 22222 33333333298 99: YES
Test siren
423068147: YES
123456789: NO
123456782: YES
422260208: YES
12345678: NO
1234567840: NO
Test siret
42306814700010: YES
12345678912345: NO
12345678200010: YES
42226020800026: YES
1234567891234x25: NO
12345678: NO
1234567840: NO
Test postalCode
01234: YES
012345: NO
0123: NO
00234: NO
99234: YES
2A234: NO
20234: YES
0123X: NO
Test region
12: YES
00: NO
1: NO
100: NO
20: NO
2A: YES
2B: YES
2C: NO
972: YES
979: NO
