--TEST--
validate_AU.phpt: Unit tests for 'Validate/AU.php'
--FILE--
<?php
// 
// Validate test script
$noYes = array('NO', 'YES');
require_once 'Validate/AU.php';

echo "Test Validate_AU\n";
echo "****************\n";

$postalCodes = array(5251, // OK
                     5000, // OK
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


$tfns = array( 
'123 456 782'
 );//OK 

$acns = array(
'000 000 019',
'000 250 000',
'000 500 005',
'000 750 005',
'001 000 004',
'001 250 004',
'001 500 009',
'001 749 999',
'001 999 999',
'002 249 998',
'002 499 998',
'002 749 993',
'002 999 993',
'003 249 992',
'003 499 992',
'003 749 988',
'003 999 988',
'004 249 987',
'004 499 987',
'004 749 982',
'004 999 982',
'005 249 981',
'005 499 981',
'005 749 986',
'005 999 977',
'006 249 976',
'006 499 976',
'006 749 980',
'006 999 980',
'007 249 989',
'007 499 989',
'007 749 975',
'007 999 975',
'008 249 974',
'008 499 974',
'008 749 979',
'008 999 979',
'009 249 969',
'009 499 969',
'009 749 964',
'009 999 964',
'010 249 966',
'010 499 966',
'010 749 961'
);//OK

$phoneNumbers = array(
	'0294599545'     => VALIDATE_AU_PHONE_NATIONAL, // OK
	'03 8779 7221'   => VALIDATE_AU_PHONE_NATIONAL, // OK
	'(03) 8779 7221' => VALIDATE_AU_PHONE_NATIONAL, // OK
	'(03)-8779-7221' => VALIDATE_AU_PHONE_NATIONAL, // OK
	'03-8779-7221'   => VALIDATE_AU_PHONE_NATIONAL, // OK
	
	'13 18 03'      => VALIDATE_AU_PHONE_INDIAL, // OK
	'13-18-03'      => VALIDATE_AU_PHONE_INDIAL, // OK
	'1300 000 000'  => VALIDATE_AU_PHONE_INDIAL, // OK
	'1900 000 000'  => VALIDATE_AU_PHONE_INDIAL, // OK
	'1800 000 000'  => VALIDATE_AU_PHONE_INDIAL, // OK
	'131803'        => VALIDATE_AU_PHONE_INDIAL | VALIDATE_AU_PHONE_STRICT, // OK
	'1300000000'    => VALIDATE_AU_PHONE_INDIAL | VALIDATE_AU_PHONE_STRICT, // OK
	'1900000000'    => VALIDATE_AU_PHONE_INDIAL | VALIDATE_AU_PHONE_STRICT, // OK
	'1800000000'    => VALIDATE_AU_PHONE_INDIAL | VALIDATE_AU_PHONE_STRICT, // OK
	
	'+61.3 8779 7221' => VALIDATE_AU_PHONE_INTERNATIONAL, // OK
	'+61.387797222'   => VALIDATE_AU_PHONE_INTERNATIONAL | VALIDATE_AU_PHONE_STRICT, // OK

	'+61.3 8779 7223' => VALIDATE_AU_PHONE_INTERNATIONAL | VALIDATE_AU_PHONE_STRICT, // NOK
	'03 3877 9100'   => VALIDATE_AU_PHONE_INTERNATIONAL, // NOK
	'61 387797222'    => VALIDATE_AU_PHONE_INTERNATIONAL, // NOK
	'+61.0 8779 7222' => VALIDATE_AU_PHONE_INTERNATIONAL, // NOK
	'61 9 8779 7222'  => VALIDATE_AU_PHONE_INTERNATIONAL, // NOK
);

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

echo "\nTest tfn\n";
foreach ($tfns as $tfn) {
    echo "{$tfn}: ".$noYes[Validate_AU::tfn($tfn)]."\n";
}

echo "\nTest phoneNumber\n";
foreach ($phoneNumbers as $phoneNumber => $flags) {
    echo "{$phoneNumber}: ".$noYes[Validate_AU::phoneNumber($phoneNumber, $flags)]."\n";
}

?>
--EXPECT--
Test Validate_AU
****************

Test postalCode without check against table
5251: YES
5000: YES
4664: YES
2491: YES
1000: YES
9999: YES
abc: NO
a7000: NO

Test postalCode with check against table (strong)
5251: YES
5000: YES
4664: NO
2491: NO
1000: NO
9999: NO
abc: NO
a7000: NO

Test abn
28 043 145 470: YES
65 497 794 289: YES
46 527 394 509: YES
99 070 045 359: YES
98 860 905 153: YES
53 106 288 699: YES
51 008 129 511: YES
43 500 713 236: YES
72 342 387 170: YES
21 188 299 895: YES
55 914 901 347: YES
92 638 328 368: YES

Test acn
000 000 019: YES
000 250 000: YES
000 500 005: YES
000 750 005: YES
001 000 004: YES
001 250 004: YES
001 500 009: YES
001 749 999: YES
001 999 999: YES
002 249 998: YES
002 499 998: YES
002 749 993: YES
002 999 993: YES
003 249 992: YES
003 499 992: YES
003 749 988: YES
003 999 988: YES
004 249 987: YES
004 499 987: YES
004 749 982: YES
004 999 982: YES
005 249 981: YES
005 499 981: YES
005 749 986: YES
005 999 977: YES
006 249 976: YES
006 499 976: YES
006 749 980: YES
006 999 980: YES
007 249 989: YES
007 499 989: YES
007 749 975: YES
007 999 975: YES
008 249 974: YES
008 499 974: YES
008 749 979: YES
008 999 979: YES
009 249 969: YES
009 499 969: YES
009 749 964: YES
009 999 964: YES
010 249 966: YES
010 499 966: YES
010 749 961: YES

Test tfn
123 456 782: YES

Test phoneNumber
0294599545: YES
03 8779 7221: YES
(03) 8779 7221: YES
(03)-8779-7221: YES
03-8779-7221: YES
13 18 03: YES
13-18-03: YES
1300 000 000: YES
1900 000 000: YES
1800 000 000: YES
131803: YES
1300000000: YES
1900000000: YES
1800000000: YES
+61.3 8779 7221: YES
+61.387797222: YES
+61.3 8779 7223: NO
03 3877 9100: NO
61 387797222: NO
+61.0 8779 7222: NO
61 9 8779 7222: NO