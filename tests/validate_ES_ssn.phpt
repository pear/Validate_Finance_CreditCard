--TEST--
validate_ES_ssn.phpt: Unit tests ssn method for 'Validate/ES.php'
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

$ssns = array ( '720111361735' => 'KO'
              , '281234567840' => 'OK'
              , '351234567825' => 'OK'
              , '35/12345678/25' => 'OK'
              , '35X1234567825' => 'KO'
              , '031322136383' => 'KO'
              , '72011a361732' => 'KO'
              , '73011a361731' => 'KO'
              , '03092a136383' => 'KO'
              , '03132a136385' => 'KO'
              , '201113617312' => 'KO'
              , '301113617334' => 'KO'
              , '309221363823' => 'KO'
              , '313221363822' => 'KO'
              );

$errorFound = false;
$errorFound = $errorFound || test_func(array('validate_ES','ssn'), $ssns );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_ES
****************
---------
Test validate_ES::ssn
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 720111361735         : KO    KO
 V 281234567840         : OK    OK
 V 351234567825         : OK    OK
 V 35/12345678/25       : OK    OK
 V 35X1234567825        : KO    KO
 V 031322136383         : KO    KO
 V 72011a361732         : KO    KO
 V 73011a361731         : KO    KO
 V 03092a136383         : KO    KO
 V 03132a136385         : KO    KO
 V 201113617312         : KO    KO
 V 301113617334         : KO    KO
 V 309221363823         : KO    KO
 V 313221363822         : KO    KO
... SUCCESS
