--TEST--
validate_AU.phpt: Unit tests for 'Validate/AU.php'
--FILE--
<?php
// 
// Validate test script
include (dirname(__FILE__) . '/validate_functions.inc');
require_once 'Validate/AU.php';

echo "Test Validate_AU\n";
echo "****************\n";

$postalCodes = array( 5251 => 'OK',
                      5000 => 'OK',
                      4662 => 'OK',
                      2470 => 'OK',
                      1000 => 'OK', // (OK if not strong)
                      9999 => 'OK', // (OK if not strong)
                      'abc' => 'KO',
                      'a7000' => 'KO',
);
    
$postalCodesStrong = array( 5251 => 'OK',
                      5000 => 'OK',
                      4662 => 'OK',
                      2470 => 'OK',
                      1000 => 'KO', // (OK if not strong)
                      9999 => 'KO', // (OK if not strong)
                      'abc' => 'KO',
                      'a7000' => 'KO',
);

$abns = array( 
'28 043 145 470' => 'OK',
'65 497 794 289' => 'OK',
'46 527 394 509' => 'OK',
'99 070 045 359' => 'OK',
'98 860 905 153' => 'OK',
'53 106 288 699' => 'OK',
'51 008 129 511' => 'OK',
'43 500 713 236' => 'OK',
'72 342 387 170' => 'OK',
'21 188 299 895' => 'OK',
'55 914 901 347' => 'OK',
'92 638 328 368' => 'OK',
 );//OK 


$tfns = array( 
'123 456 782' => 'OK',
 );//OK 

$acns = array(
'000 000 019' => 'OK',
'000 250 000' => 'OK',
'000 500 005' => 'OK',
'000 750 005' => 'OK',
'001 000 004' => 'OK',
'001 250 004' => 'OK',
'001 500 009' => 'OK',
'001 749 999' => 'OK',
'001 999 999' => 'OK',
'002 249 998' => 'OK',
'002 499 998' => 'OK',
'002 749 993' => 'OK',
'002 999 993' => 'OK',
'003 249 992' => 'OK',
'003 499 992' => 'OK',
'003 749 988' => 'OK',
'003 999 988' => 'OK',
'004 249 987' => 'OK',
'004 499 987' => 'OK',
'004 749 982' => 'OK',
'004 999 982' => 'OK',
'005 249 981' => 'OK',
'005 499 981' => 'OK',
'005 749 986' => 'OK',
'005 999 977' => 'OK',
'006 249 976' => 'OK',
'006 499 976' => 'OK',
'006 749 980' => 'OK',
'006 999 980' => 'OK',
'007 249 989' => 'OK',
'007 499 989' => 'OK',
'007 749 975' => 'OK',
'007 999 975' => 'OK',
'008 249 974' => 'OK',
'008 499 974' => 'OK',
'008 749 979' => 'OK',
'008 999 979' => 'OK',
'009 249 969' => 'OK',
'009 499 969' => 'OK',
'009 749 964' => 'OK',
'009 999 964' => 'OK',
'010 249 966' => 'OK',
'010 499 966' => 'OK',
'010 749 961' => 'OK',
);//OK

$errorFound = false;
	
$errorFound = $errorFound || test_func(array('validate_AU','postalCode'), $postalCodes );
$errorFound = $errorFound || test_func(array('validate_AU','postalCode'), $postalCodesStrong,true);
$errorFound = $errorFound || test_func(array('validate_AU','abn'), $abns );
$errorFound = $errorFound || test_func(array('validate_AU','acn'), $acns );
$errorFound = $errorFound || test_func(array('validate_AU','tfn'), $tfns );

?>
--EXPECT--
Test Validate_AU
****************
---------
Test validate_AU::postalCode
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 5251                 : OK    OK
 V 5000                 : OK    OK
 V 4662                 : OK    OK
 V 2470                 : OK    OK
 V 1000                 : OK    OK
 V 9999                 : OK    OK
 V abc                  : KO    KO
 V a7000                : KO    KO
---------
Test validate_AU::postalCode
extra params:1
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 5251                 : OK    OK
 V 5000                 : OK    OK
 V 4662                 : OK    OK
 V 2470                 : OK    OK
 V 1000                 : KO    KO
 V 9999                 : KO    KO
 V abc                  : KO    KO
 V a7000                : KO    KO
---------
Test validate_AU::abn
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 28 043 145 470       : OK    OK
 V 65 497 794 289       : OK    OK
 V 46 527 394 509       : OK    OK
 V 99 070 045 359       : OK    OK
 V 98 860 905 153       : OK    OK
 V 53 106 288 699       : OK    OK
 V 51 008 129 511       : OK    OK
 V 43 500 713 236       : OK    OK
 V 72 342 387 170       : OK    OK
 V 21 188 299 895       : OK    OK
 V 55 914 901 347       : OK    OK
 V 92 638 328 368       : OK    OK
---------
Test validate_AU::acn
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 000 000 019          : OK    OK
 V 000 250 000          : OK    OK
 V 000 500 005          : OK    OK
 V 000 750 005          : OK    OK
 V 001 000 004          : OK    OK
 V 001 250 004          : OK    OK
 V 001 500 009          : OK    OK
 V 001 749 999          : OK    OK
 V 001 999 999          : OK    OK
 V 002 249 998          : OK    OK
 V 002 499 998          : OK    OK
 V 002 749 993          : OK    OK
 V 002 999 993          : OK    OK
 V 003 249 992          : OK    OK
 V 003 499 992          : OK    OK
 V 003 749 988          : OK    OK
 V 003 999 988          : OK    OK
 V 004 249 987          : OK    OK
 V 004 499 987          : OK    OK
 V 004 749 982          : OK    OK
 V 004 999 982          : OK    OK
 V 005 249 981          : OK    OK
 V 005 499 981          : OK    OK
 V 005 749 986          : OK    OK
 V 005 999 977          : OK    OK
 V 006 249 976          : OK    OK
 V 006 499 976          : OK    OK
 V 006 749 980          : OK    OK
 V 006 999 980          : OK    OK
 V 007 249 989          : OK    OK
 V 007 499 989          : OK    OK
 V 007 749 975          : OK    OK
 V 007 999 975          : OK    OK
 V 008 249 974          : OK    OK
 V 008 499 974          : OK    OK
 V 008 749 979          : OK    OK
 V 008 999 979          : OK    OK
 V 009 249 969          : OK    OK
 V 009 499 969          : OK    OK
 V 009 749 964          : OK    OK
 V 009 999 964          : OK    OK
 V 010 249 966          : OK    OK
 V 010 499 966          : OK    OK
 V 010 749 961          : OK    OK
---------
Test validate_AU::tfn
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 123 456 782          : OK    OK