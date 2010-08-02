--TEST--
validate_ES_ccc.phpt: Unit tests ccc method for 'Validate/ES.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_functions.inc');
if (is_file(dirname(__FILE__) . '/../Validate/ES.php')) {
    require_once dirname(__FILE__) . '/../Validate/ES.php';
    $dataDir = dirname(__FILE__) . '/../data';
} else {
    require_once 'Validate/ES.php';
    $dataDir = null;
}

echo "Test Validate_ES\n";
echo "****************\n";

$cccs = array ( '2077 0024 00 3102575766' => 'OK'
              , '2034 4505 73 1000034682' => 'KO'
              , '0000 0000 00 0000000000' => 'OK'
              , '0' => 'KO'
              , '1111 1111 11 1111111111' => 'KO'
              , '0001 0001 65 0000000001' => 'OK'
              , '' => 'KO'
              );

$errorFound = false;
$errorFound = $errorFound || test_func(array('validate_ES','ccc'), $cccs );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_ES
****************
---------
Test validate_ES::ccc
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 2077 0024 00 3102575766 : OK    OK
 V 2034 4505 73 1000034682 : KO    KO
 V 0000 0000 00 0000000000 : OK    OK
 V 0                    : KO    KO
 V 1111 1111 11 1111111111 : KO    KO
 V 0001 0001 65 0000000001 : OK    OK
 V                      : KO    KO
... SUCCESS
