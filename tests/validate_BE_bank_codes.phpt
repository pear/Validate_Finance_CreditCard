--TEST--
validate_BE_bank_codes.phpt: Unit tests for bank code method 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_functions.inc');
require_once 'Validate/BE.php';

echo "Test bank code Validate_BE\n";
echo "**************************\n";


$bankCodeList = array( '310164533207' => 'OK'
,                      '310164533227' => 'KO'
,                      '31c164533207' => 'KO'
,                      '096011784309' => 'OK'
,                      '310-164533207' => 'OK'
,                      '310-164533227' => 'KO'
,                      '310-1645332-07' => 'OK'
,                      '310-1645332-27' => 'KO'
,                      '310.1645332.07' => 'OK'
,                      '310.1645332.27' => 'KO'
,                      '310 1645332 07' => 'OK'
,                      '310 1645332 27' => 'KO'
,
);

$errorFound = false;
$errorFound = $errorFound || test_func(array('validate_BE','bankCode'), $bankCodeList );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';

?>
--EXPECT--
Test bank code Validate_BE
**************************
---------
Test validate_BE::bankCode
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 310164533207         : OK    OK
 V 310164533227         : KO    KO
 V 31c164533207         : KO    KO
 V 096011784309         : OK    OK
 V 310-164533207        : OK    OK
 V 310-164533227        : KO    KO
 V 310-1645332-07       : OK    OK
 V 310-1645332-27       : KO    KO
 V 310.1645332.07       : OK    OK
 V 310.1645332.27       : KO    KO
 V 310 1645332 07       : OK    OK
 V 310 1645332 27       : KO    KO
... SUCCESS