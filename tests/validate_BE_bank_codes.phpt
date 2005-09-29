--TEST--
validate_BE_bank_codes.phpt: Unit tests for bank code method 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_BE_functions.inc.php');
require_once 'Validate/BE.php';

echo "Test bank code Validate_BE\n";
echo "**************************\n";


$bankCodeList = array( '310164533207' => 'OK'// OK
,                      '310164533227' => 'KO' // NOK
,                      '31c164533207' => 'KO' // NOK
,                      '096011784309' => 'OK' // OK
,                      '310-164533207' => 'OK'// OK
,                      '310-164533227' => 'KO' // NOK
,                      '310-1645332-07' => 'OK'// OK
,                      '310-1645332-27' => 'KO' // NOK
,                      '310.1645332.07' => 'OK'// OK
,                      '310.1645332.27' => 'KO' // NOK
,                      '310 1645332 07' => 'OK'// OK
,                      '310 1645332 27' => 'KO' // NOK
,
);

echo (test_func('bankCode', $bankCodeList )) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test bank code Validate_BE
**************************
---------
Test bankCode
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