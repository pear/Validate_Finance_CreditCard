--TEST--
validate_BE.phpt: Unit tests for 'Validate/BE.php'
--FILE--
<?php

require_once 'Validate/BE.php';

echo "Test Validate_BE\n";
echo "****************\n";

$noYes = array('KO', 'OK');


$vats = array( '202239951'    => 'OK'// OK
             , '202.239.951'  => 'OK' // NOK
             , '202-239-951'  => 'OK' // NOK
             , '102.239.951'  => 'KO' // NOK
             , '2o2239951'    => 'KO'// OK
             , '2o2.239.951'  => 'KO' // NOK
             , '2o2-239-951'  => 'KO' // NOK
             , '2002239951'   => 'KO'// OK
             , '2002.239.951' => 'KO' // NOK
             ,); 
                  
$bankcodes = array( '310164533207' => 'OK'// OK
                  , '310164533227' => 'KO' // NOK
                  , '31c164533207' => 'KO' // NOK
                  , '096011784309' => 'OK' // OK
                  , '310-164533207' => 'OK'// OK
                  , '310-164533227' => 'KO' // NOK
                  , '310-1645332-07' => 'OK'// OK
                  , '310-1645332-27' => 'KO' // NOK
                  , '310.1645332.07' => 'OK'// OK
                  , '310.1645332.27' => 'KO' // NOK
                  , '310 1645332 07' => 'OK'// OK
                  , '310 1645332 27' => 'KO' // NOK
                  , 
                  ); 

$phonesnumbers = array( '065 12 34 56'        => 'OK'// national little zone (phone number with 6 number)
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

$postalCodes = array('b-1234' => 'OK', // OK
                     'B-1234' => 'OK', // OK
                     'b1234'  => 'OK', // OK
                     'B1234'  => 'OK', // OK
                     '1234'   => 'OK', // OK
                     'b-3840' => 'OK', // OK
                     'B-3840' => 'OK', // OK
                     'b3840'  => 'OK', // OK
                     'B3840'  => 'OK', // OK
                     '3840'   => 'OK', // OK
                     '012345' => 'KO', // NOK
                     '123'    => 'KO', // NOK
                     '0234'   => 'KO', // NOK
                     '7234'   => 'OK', // OK
                     '2A34'   => 'KO', // NOK
                     '023X'   => 'KO'); // NOK

$postalCodesStrong = array('b-1234' => 'KO', // OK
                           'B-1234' => 'KO', // OK
                           'b1234'  => 'KO', // OK
                           'B1234'  => 'KO', // OK
                           '1234'   => 'KO', // OK
                           'b-3840' => 'OK', // OK
                           'B-3840' => 'OK', // OK
                           'b3840'  => 'OK', // OK
                           'B3840'  => 'OK', // OK
                           '3840'   => 'OK', // OK
                           '012345' => 'KO', // NOK
                           '123'    => 'KO', // NOK
                           '0234'   => 'KO', // NOK
                           '7234'   => 'KO', // OK
                           '2A34'   => 'KO', // NOK
                           '023X'   => 'KO'); // NOK

                     
$nationalId = array( '73011136173'  => 'OK'// OK it's mine
                   , '73.01.11.361-73'  => 'OK'// OK it's mine
                   , '730111-361-73'  => 'OK'// OK it's mine
                   , '730111-361-73'  => 'OK'// OK it's mine
                   , '730111-361-99'  => 'KO'// NOK invalid mod
                   , '730211-361-99'  => 'OK'// NOK invalid mod
                   );
                   
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


  $errorFound = $errorFound || test_func('vat', $vats );
  $errorFound = $errorFound || test_func('ssn', $ssns );
  $errorFound = $errorFound || test_func('phoneNumber', $phonesnumbers );
  $errorFound = $errorFound || test_func('postalCode', $postalCodes );
  $errorFound = $errorFound || test_func('postalCode', $postalCodesStrong, true );
  $errorFound = $errorFound || test_func('bankcode', $bankcodes );

function test_func($func_name, $data ,$scndParam=NULL)
{
    $noYes = array('KO', 'OK');
  
    echo "---------\nTest " . $func_name . "\n";
    echo ' _ Value                  State Return' . "\n";
    echo ' V = validation result is right' . "\n";
    echo ' X = validation result is wrong' . "\n";

    foreach ($data as $value => $resultWaited) {
                
        if(!is_null($scndParam))
        {
            $result = $noYes[Validate_BE::$func_name($value,$scndParam)];
        }
        else
        
        $result = $noYes[Validate_BE::$func_name($value)];
        
        echo  ($resultWaited == $result  
        ? ' V ' 
        : '!X!')
        .str_pad($value, 20) . " : ".$resultWaited . '    ' . $result ."\n";
         
    }
    return ($resultWaited != $result );

}


 echo ($errorFound) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_BE
****************
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
---------
Test postalCode
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V b-1234               : OK    OK
 V B-1234               : OK    OK
 V b1234                : OK    OK
 V B1234                : OK    OK
 V 1234                 : OK    OK
 V b-3840               : OK    OK
 V B-3840               : OK    OK
 V b3840                : OK    OK
 V B3840                : OK    OK
 V 3840                 : OK    OK
 V 012345               : KO    KO
 V 123                  : KO    KO
 V 0234                 : KO    KO
 V 7234                 : OK    OK
 V 2A34                 : KO    KO
 V 023X                 : KO    KO
---------
Test postalCode
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V b-1234               : KO    KO
 V B-1234               : KO    KO
 V b1234                : KO    KO
 V B1234                : KO    KO
 V 1234                 : KO    KO
 V b-3840               : OK    OK
 V B-3840               : OK    OK
 V b3840                : OK    OK
 V B3840                : OK    OK
 V 3840                 : OK    OK
 V 012345               : KO    KO
 V 123                  : KO    KO
 V 0234                 : KO    KO
 V 7234                 : KO    KO
 V 2A34                 : KO    KO
 V 023X                 : KO    KO
---------
Test bankcode
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