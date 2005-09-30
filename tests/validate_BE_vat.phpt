--TEST--
validate_BE_vat.phpt: Unit tests for vat check method 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_BE_functions.inc.php');
require_once 'Validate/BE.php';

echo "Test vat Validate_BE\n";
echo "********************\n";

$vats = array( '202239951'    => 'OK'
,              '202.239.951'  => 'OK'
,              '202-239-951'  => 'OK'
,              '102.239.951'  => 'KO'
,              '2o2239951'    => 'KO'
,              '2o2.239.951'  => 'KO'
,              '2o2-239-951'  => 'KO'
,              '2002239951'   => 'KO'
,              '2002.239.951' => 'KO'
,);

echo (test_func('vat', $vats )) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test vat Validate_BE
********************
---------
Test vat
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 202239951            : OK    OK
 V 202.239.951          : OK    OK
 V 202-239-951          : OK    OK
 V 102.239.951          : KO    KO
 V 2o2239951            : KO    KO
 V 2o2.239.951          : KO    KO
 V 2o2-239-951          : KO    KO
 V 2002239951           : KO    KO
 V 2002.239.951         : KO    KO
... SUCCESS