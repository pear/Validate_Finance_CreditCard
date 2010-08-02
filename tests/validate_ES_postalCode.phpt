--TEST--
validate_ES_postalCode.phpt: Unit tests postalCode method for 'Validate/ES.php'
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

$postalCodes = array ( '28080' => 'OK'
              , '35500' => 'OK'
              , '59000' => 'KO'
              , '12012' => 'OK'
              , '25120' => 'OK'
              , '10' => 'KO'
              , 'X123' => 'KO'
              );

$errorFound = false;
$errorFound = $errorFound || test_func(array('validate_ES','postalCode'), $postalCodes );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_ES
****************
---------
Test validate_ES::postalCode
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 28080                : OK    OK
 V 35500                : OK    OK
 V 59000                : KO    KO
 V 12012                : OK    OK
 V 25120                : OK    OK
 V 10                   : KO    KO
 V X123                 : KO    KO
... SUCCESS
