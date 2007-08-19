--TEST--
validate_BE_post_code.phpt: Unit tests for post code method 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_functions.inc');
if (is_file(dirname(__FILE__) . '/../Validate/BE.php')) {
    require_once dirname(__FILE__) . '/../Validate/BE.php';
    $dataDir = '../data';
} else {
    require_once 'Validate/BE.php';
    $dataDir = null;
}

echo "Test Post Code Validate_BE\n";
echo "**************************\n";

$postalCodeList = array('b-1234' => 'OK',
                        'B-1234' => 'OK',
                        'b1234'  => 'OK',
                        'B1234'  => 'OK',
                        '1234'   => 'OK',
                        'b-3840' => 'OK',
                        'B-3840' => 'OK',
                        'b3840'  => 'OK',
                        'B3840'  => 'OK',
                        '3840'   => 'OK',
                        '012345' => 'KO',
                        '123'    => 'KO',
                        '0234'   => 'KO',
                        '7234'   => 'OK',
                        '2A34'   => 'KO',
                        '023X'   => 'KO');
$errorFound = false;
$errorFound =
$errorFound
||
test_func( array('validate_BE','postalCode'), $postalCodeList, array(false, $dataDir) );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';

?>
--EXPECT--
Test Post Code Validate_BE
**************************
---------
Test validate_BE::postalCode
extra params:|../data
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V b-1234               : OK    OK
 V B-1234               : OK    OK
 V b1234                : OK    OK
 V B1234                : OK    OK
 V 1234                 : OK    OK
 V b-3840               : OK    OK
 V B-3840               : OK    OK
 V b3840                : OK    OK
 V B3840                : OK    OK
 V 3840                 : OK    OK
 V 012345               : KO    KO
 V 123                  : KO    KO
 V 0234                 : KO    KO
 V 7234                 : OK    OK
 V 2A34                 : KO    KO
 V 023X                 : KO    KO
... SUCCESS
