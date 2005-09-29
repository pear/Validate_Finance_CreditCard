--TEST--
validate_BE_ssn.phpt: Unit tests ssn method for 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_BE_functions.inc.php');
require_once 'Validate/BE.php';

echo "Test Validate_BE\n";
echo "****************\n";

$ssns = array ( '72011136173' => 'KO'
              , '73011136173' => 'OK'
              , '03092213638' => 'OK'
              , '03132213638' => 'KO'
              , '72011a36173' => 'KO'
              , '73011a36173' => 'KO'
              , '03092a13638' => 'KO'
              , '03132a13638' => 'KO'
              , '2011136173'  => 'KO'
              , '3011136173'  => 'KO'
              , '3092213638'  => 'KO'
              , '3132213638'  => 'KO'
              );

$errorFound = false;


  $errorFound = $errorFound || test_func('ssn', $ssns );

 echo ($errorFound) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_BE
****************
---------
Test ssn
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 72011136173          : KO    KO
 V 73011136173          : OK    OK
 V 03092213638          : OK    OK
 V 03132213638          : KO    KO
 V 72011a36173          : KO    KO
 V 73011a36173          : KO    KO
 V 03092a13638          : KO    KO
 V 03132a13638          : KO    KO
 V 2011136173           : KO    KO
 V 3011136173           : KO    KO
 V 3092213638           : KO    KO
 V 3132213638           : KO    KO
... SUCCESS