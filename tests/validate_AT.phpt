--TEST--
validate_AT.phpt: Unit tests for 'Validate/AT.php'
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
include (dirname(__FILE__).'/validate_functions.inc');
if (is_file(dirname(__FILE__) . '/../Validate/AT.php')) {
    require_once dirname(__FILE__) . '/../Validate/AT.php';
    $dataDir = '../data';
} else {
    require_once 'Validate/AT.php';
    $dataDir = null;
}

echo "Test Validate_AT\n";
echo "****************\n";

$postalCodes = array( 7033 => 'OK',
                     7000 => 'OK',
                     4664 => 'OK',
                     2491 => 'OK',
                     1000 => 'OK', // (OK if not strong)
                     9999 => 'OK', // (OK if not strong)
                     'abc' => 'KO',
                     'a7000' => 'KO',
);

$postalCodesStrong = array( 7033 => 'OK',
                     7000 => 'OK',
                     4664 => 'OK',
                     2491 => 'OK',
                     1000 => 'KO', // (OK if not strong)
                     9999 => 'KO', // (OK if not strong)
                     'abc' => 'KO',
                     'a7000' => 'KO',
);

$ssns = array( '4298 02-12-82' => 'OK',
               '4298001282' => 'KO',
               '1508-10-13-50' => 'KO',
               '1508101050' => 'OK',
               '1508101051' => 'KO',
               '4290021282' => 'KO',
               '21 34 23 12 74' => 'KO',
);


$regions = array(
    "AU-06" => 'OK',
    "2"     => 'OK',
    "AU-00" => 'KO',
    );

$errorFound = false;
$errorFound = $errorFound || test_func(array('Validate_AT','ssn'        ), $ssns        );
$errorFound = $errorFound || test_func(array('Validate_AT','postalCode' ), $postalCodesStrong, array(false, $dataDir));
$errorFound = $errorFound || test_func(array('Validate_AT','postalCode' ), $postalCodesStrong, array(true, $dataDir));
$errorFound = $errorFound || test_func(array('Validate_AT','postalCode' ), $postalCodes,false );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';



?>
--EXPECT--
Test Validate_AT
****************
---------
Test Validate_AT::ssn
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 4298 02-12-82        : OK    OK
 V 4298001282           : KO    KO
 V 1508-10-13-50        : KO    KO
 V 1508101050           : OK    OK
 V 1508101051           : KO    KO
 V 4290021282           : KO    KO
 V 21 34 23 12 74       : KO    KO
---------
Test Validate_AT::postalCode
extra params:|../data
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 7033                 : OK    OK
 V 7000                 : OK    OK
 V 4664                 : OK    OK
 V 2491                 : OK    OK
!X!1000                 : KO    OK
!X!9999                 : KO    OK
 V abc                  : KO    KO
 V a7000                : KO    KO
---------
Test Validate_AT::postalCode
extra params:1|../data
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 7033                 : OK    OK
 V 7000                 : OK    OK
 V 4664                 : OK    OK
 V 2491                 : OK    OK
 V 1000                 : KO    KO
 V 9999                 : KO    KO
 V abc                  : KO    KO
 V a7000                : KO    KO
---------
Test Validate_AT::postalCode
extra params:
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 7033                 : OK    OK
 V 7000                 : OK    OK
 V 4664                 : OK    OK
 V 2491                 : OK    OK
 V 1000                 : OK    OK
 V 9999                 : OK    OK
 V abc                  : KO    KO
 V a7000                : KO    KO
... SUCCESS
