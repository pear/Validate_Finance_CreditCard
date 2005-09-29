--TEST--
validate_BE_bank_transfer_message.phpt: Unit tests for bank transfert message method 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_BE_functions.inc.php');
require_once 'Validate/BE.php';

echo "Test bank Transfer Message Validate_BE\n";
echo "**************************************\n";


$bankTransferMessageList = array( '054/3140/16211' => 'OK'// OK
                                , '053/3140/16211' => 'KO'// NOK
                                , '054.3140.16211' => 'OK'// OK
                                , '053.3140.16211' => 'KO'// NOK
                                , '054-3140-16211' => 'OK'// OK
                                , '053-3140-16211' => 'KO'// NOK
                                , '054 3140 16211' => 'OK'// OK
                                , '053 3140 16211' => 'KO'// NOK
                                , '054314016211' => 'OK'// NOK
                                , '053314016211' => 'KO'// OK
                                , '54314016211' => 'KO'// NOK
                                );

echo ( test_func('bankTransferMessage', $bankTransferMessageList )) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test bank Transfer Message Validate_BE
**************************************
---------
Test bankTransferMessage
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 054/3140/16211       : OK    OK
 V 053/3140/16211       : KO    KO
 V 054.3140.16211       : OK    OK
 V 053.3140.16211       : KO    KO
 V 054-3140-16211       : OK    OK
 V 053-3140-16211       : KO    KO
 V 054 3140 16211       : OK    OK
 V 053 3140 16211       : KO    KO
 V 054314016211         : OK    OK
 V 053314016211         : KO    KO
 V 54314016211          : KO    KO
... SUCCESS