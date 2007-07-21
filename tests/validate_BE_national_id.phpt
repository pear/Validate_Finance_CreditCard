--TEST--
validate_BE.phpt: Unit tests for 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_functions.inc');
require_once 'Validate/BE.php';

$nationalIdList = array( '73011136173'  => 'OK'
                       , '73.01.11.361-73'  => 'OK'
                       , '730111-361-73'  => 'OK'
                       , '730111 361 73'  => 'OK'
                       , '730111-361-99'  => 'KO'
                       , '730211-361-99'  => 'KO'
                       );

echo "Test Validate_BE::nationalId()\n";
echo "******************************\n";


$errorFound = false;
$errorFound = $errorFound || test_func(array('validate_BE','nationalId'), $nationalIdList );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_BE::nationalId()
******************************
---------
Test validate_BE::nationalId
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 73011136173          : OK    OK
 V 73.01.11.361-73      : OK    OK
 V 730111-361-73        : OK    OK
 V 730111 361 73        : OK    OK
 V 730111-361-99        : KO    KO
 V 730211-361-99        : KO    KO
... SUCCESS