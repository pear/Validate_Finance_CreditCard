--TEST--
validate_BE.phpt: Unit tests for 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_BE_functions.inc.php');
require_once 'Validate/BE.php';

$nationalIdList = array( '73011136173'  => 'OK'
                       , '73.01.11.361-73'  => 'OK'
                       , '730111-361-73'  => 'OK'
                       , '730111 361 73'  => 'OK'
                       , '730111-361-99'  => 'KO'
                       , '730211-361-99'  => 'KO'
                       );


$functionToTest = 'nationalId';
$title = 'Test Validate_BE::' . $functionToTest . '()';
echo $title . "\n";
echo str_pad('',strlen($title),'*') . "\n";
echo (test_func($functionToTest, $nationalIdList )) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_BE::nationalId()
******************************
---------
Test nationalId
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