--TEST--
validate_BE.phpt: Unit tests for 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_BE_functions.inc.php');
require_once 'Validate/BE.php';

$nationalIdList = array( '73011136173'  => 'OK'// OK it's mine
                   , '73.01.11.361-73'  => 'OK'// OK it's mine
                   , '730111-361-73'  => 'OK'// OK it's mine
                   , '730111-361-73'  => 'OK'// OK it's mine
                   , '730111-361-99'  => 'KO'// NOK invalid mod
                   , '730211-361-99'  => 'OK'// NOK invalid mod
                   );


$functionToTest = 'nationalId';
$title = 'Test Validate_BE::' . $functionToTest . '()';
echo $title . "\n";
echo str_pad('',strlen($title),'*') . "\n";
echo (test_func($functionToTest, $nationalIdList )) ? '... FAILED' : '... SUCCESS';

 echo (test_func('nationalId', $nationalIdList )) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_BE::nationalId()
******************************
---------
Test nationalId
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