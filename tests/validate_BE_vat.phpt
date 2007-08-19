--TEST--
validate_BE_vat.phpt: Unit tests for vat check method 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_functions.inc');
if (is_file(dirname(__FILE__) . '/../Validate/BE.php')) {
    require_once dirname(__FILE__) . '/../Validate/BE.php';
    $dataDir = dirname(__FILE__) . '/../data';
} else {
    require_once 'Validate/BE.php';
    $dataDir = null;
}

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

$errorFound = false;
$errorFound = $errorFound || test_func(array('validate_BE','vat'), $vats );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test vat Validate_BE
********************
---------
Test validate_BE::vat
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
