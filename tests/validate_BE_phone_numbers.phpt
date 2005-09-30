--TEST--
validate_BE_phone_numbers: Unit tests for phoneNumber method in 'Validate/BE.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_BE_functions.inc.php');
require_once 'Validate/BE.php';

$phoneNumberList = array( '065 12 34 56'        => 'OK'// national little zone (phone number with 6 number)
                      , '02 123 45 67'        => 'OK'// national big  zone (phone number with 6 number)
                      , '0485 12 34 56'       => 'OK'// GSM
                      , '00 32 65 12 34 56'   => 'OK'// national little zone (phone number with 6 number)
                      , '00 32 2 123 45 67'   => 'OK'// national big  zone (phone number with 6 number)
                      , '00 32 485 12 34 56'  => 'OK'// GSM
                      , '+32 65 12 34 56'     => 'OK'
                      , '+32 2 123 45 67'     => 'OK'
                      , '+32 485 12 34 56'    => 'OK'
                      , '++ 32 65 12 34 56'   => 'OK'
                      , '++ 32 2 123 45 67'   => 'OK'
                      , '++ 32 485 12 34 56'  => 'OK'
                      , '02 1a3 45 67'        => 'KO'
                      , '0485 a2 34 56'       => 'KO'
                      , '00 32 a5 12 34 56'   => 'KO'
                      , '00 32 a 123 45 67'   => 'KO'
                      , '00 32 4a5 12 34 56'  => 'KO'
                      , '017 12 34 56'        => 'KO'// national little zone (phone number with 6 number) but this prefix don't exist in belgium
                      , '+ 32 6a 12 34 56'    => 'KO'
                      , '+ 32 2 1a3 45 67'    => 'KO'
                      , '+ 32 485 1a 34 56'   => 'KO'
                      , '++ 32 65 1a 34 56'   => 'KO'
                      , '++ 32 2 1a3 45 67'   => 'KO'
                      , '++ 32 485 1a 34 56'  => 'KO'
                      , '02 12 45 67'         => 'KO'
                      , '0485 112 34 56'      => 'KO'
                      , '00 32 5 12 34 56'    => 'KO'
                      , '00 32 12 123 45 67'  => 'KO'
                      , '00 32 45 12 34 56'   => 'OK'
                      , '+ 32 651 12 34 56'   => 'KO'
                      , '+ 32 2 13 45 67'     => 'KO'
                      , '+ 32 4851 12 34 56'  => 'KO'
                      , '++ 32 65 112 34 56'  => 'KO'
                      , '++ 32 2 1213 45 67'  => 'KO'
                      , '++ 32 485 112 34 56' => 'KO'

                      );
$functionToTest = 'phoneNumber';
$title = 'Test Validate_BE::' . $functionToTest . '()';
echo $title . "\n";
echo str_pad('',strlen($title),'*') . "\n";
echo (test_func($functionToTest, $phoneNumberList )) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_BE::phoneNumber()
*******************************
---------
Test phoneNumber
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 065 12 34 56         : OK    OK
 V 02 123 45 67         : OK    OK
 V 0485 12 34 56        : OK    OK
 V 00 32 65 12 34 56    : OK    OK
 V 00 32 2 123 45 67    : OK    OK
 V 00 32 485 12 34 56   : OK    OK
 V +32 65 12 34 56      : OK    OK
 V +32 2 123 45 67      : OK    OK
 V +32 485 12 34 56     : OK    OK
 V ++ 32 65 12 34 56    : OK    OK
 V ++ 32 2 123 45 67    : OK    OK
 V ++ 32 485 12 34 56   : OK    OK
 V 02 1a3 45 67         : KO    KO
 V 0485 a2 34 56        : KO    KO
 V 00 32 a5 12 34 56    : KO    KO
 V 00 32 a 123 45 67    : KO    KO
 V 00 32 4a5 12 34 56   : KO    KO
 V 017 12 34 56         : KO    KO
 V + 32 6a 12 34 56     : KO    KO
 V + 32 2 1a3 45 67     : KO    KO
 V + 32 485 1a 34 56    : KO    KO
 V ++ 32 65 1a 34 56    : KO    KO
 V ++ 32 2 1a3 45 67    : KO    KO
 V ++ 32 485 1a 34 56   : KO    KO
 V 02 12 45 67          : KO    KO
 V 0485 112 34 56       : KO    KO
 V 00 32 5 12 34 56     : KO    KO
 V 00 32 12 123 45 67   : KO    KO
 V 00 32 45 12 34 56    : OK    OK
 V + 32 651 12 34 56    : KO    KO
 V + 32 2 13 45 67      : KO    KO
 V + 32 4851 12 34 56   : KO    KO
 V ++ 32 65 112 34 56   : KO    KO
 V ++ 32 2 1213 45 67   : KO    KO
 V ++ 32 485 112 34 56  : KO    KO
... SUCCESS