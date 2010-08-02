--TEST--
validate_ES_dni.phpt: Unit tests dni method for 'Validate/ES.php'
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

$dnis = array ( '12345678Z' => 'OK'
              , '00000000T' => 'OK'
              , '0T' => 'OK'
              , '00000000-T' => 'OK'
              , '87654321X' => 'OK'
              , '87654321J' => 'KO'
              , '123456781' => 'KO'
              , 'X12345678' => 'KO'
              , '123K' => 'KO'
              , '43215678X' => 'KO'
              , '' => 'KO'
              );

$errorFound = false;
$errorFound = $errorFound || test_func(array('validate_ES','dni'), $dnis );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_ES
****************
---------
Test validate_ES::dni
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 12345678Z            : OK    OK
 V 00000000T            : OK    OK
 V 0T                   : OK    OK
 V 00000000-T           : OK    OK
 V 87654321X            : OK    OK
 V 87654321J            : KO    KO
 V 123456781            : KO    KO
 V X12345678            : KO    KO
 V 123K                 : KO    KO
 V 43215678X            : KO    KO
 V                      : KO    KO
... SUCCESS
