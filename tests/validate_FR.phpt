--TEST--
validate_FR.phpt: Unit tests for 'Validate/FR.php'
--FILE--
<?php
// $Id$

include (dirname(__FILE__).'/validate_functions.inc');

require_once dirname(__FILE__) . '/../Validate/FR.php';

echo "Test Validate_FR\n";
echo "****************\n";

$noYes = array('KO', 'OK');
$symbol = array('!X!', ' V ');

$ssns = array(  '156077851718185' => 'OK',
'2781120050003' => 'KO', // too short
'278102305000331' => 'OK',
'278112005000332' => 'KO', // bad checksum
'278112B0500033112' => 'KO', // too long
'278112005000331' => 'OK',
'278112005000339' => 'KO', // bad checksum
'578112005000375' => 'KO', // bad sex
'278132005000363' => 'KO', // bad month
'278130005000321' => 'KO', // bad dept
'2x8112005000331' => 'KO', // NOK bad alpha
);

$ribs = array(  '20041 01003 0175293T024 33' => 'OK',
'45499 91289 01697111 65'    => 'OK',
'20041 01003 0175293T024 34' => 'KO',
'45499 91289 01697111 5'     => 'KO',
'12345 12345 12345678901 46' => 'OK',
'1234 5123451 2345678901 46' => 'KO',
'12345 12345 12345678901 47' => 'KO',
'12345 12345 1234E67H901 46' => 'OK',
'12345 12345 1234E67B901 46' => 'KO',
'12345 12345 VALIDATEURS 01' => 'OK',
'12345 12345 1234E67H901 45' => 'KO',
'12345 12345 VALIDATEURS 00' => 'KO',
'12345 12345 51394135492 01' => 'OK',
'11111 22222 33333333333 91' => 'OK',
'11111 22222 33333333298 02' => 'OK',
'11111 22222 33333333298 99' => 'OK',
);

$sirens = array('423068147'  => 'OK',
'123456789'  => 'KO',
'123456782'  => 'OK',
'422260208'  => 'OK',
'12345678'   => 'KO',
'1234567840' => 'KO',
);

$sirets = array('42306814700010'   => 'OK',
'12345678912345'   => 'KO', //
'12345678200010'   => 'OK',
'42226020800026'   => 'OK',
'1234567891234x25' => 'KO', //
'12345678'         => 'KO', //
'1234567840'       => 'KO',
); // NOK

$postalCodes = array('01234' => 'OK',
'012345' => 'KO',
'0123' => 'KO',
'00234' => 'KO',
'99234' => 'OK',
'2A234' => 'KO',
'20234' => 'OK',
'0123X' => 'KO',
);

$regions = array('12'  => 'OK',
'00'  => 'KO',
'1'   => 'KO',
'100' => 'KO',
'20'  => 'KO',
'2A'  => 'OK',
'2B'  => 'OK',
'2C'  => 'KO',
'972' => 'OK',
'979' => 'KO',
);


$errorFound = false;
$errorFound = $errorFound || test_func(array('Validate_FR','ssn'        ), $ssns        );
$errorFound = $errorFound || test_func(array('Validate_FR','siren'      ), $sirens      );
$errorFound = $errorFound || test_func(array('Validate_FR','siret'      ), $sirets      );
$errorFound = $errorFound || test_func(array('Validate_FR','postalCode' ), $postalCodes );
$errorFound = $errorFound || test_func(array('Validate_FR','region'     ), $regions     );

// rib need an array as parameters. test_func is not ready for that.

echo "---------\nTest Validate_FR::rib\n";
echo ' _ Value                            State Return' . "\n";
echo ' V = validation result is right' . "\n";
echo ' X = validation result is wrong' . "\n";

foreach ($ribs as $rib => $state) {
    $arib = explode(' ', $rib);
    $resultWaited = $noYes[Validate_FR::rib(
    array( 'codebanque'  => $arib[0],
    'codeguichet' => $arib[1],
    'nocompte'    => $arib[2],
    'key'         => $arib[3])
    )];

    echo  ($resultWaited == $state ? ' V ' : '!X!')
    .     str_pad($rib, 30) . " : " . $state . '    ' . $resultWaited . "\n";

}

echo ($errorFound) ? '... FAILED' : '... SUCCESS';

?>
--EXPECT--
Test Validate_FR
****************
---------
Test Validate_FR::ssn
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 156077851718185      : OK    OK
 V 2781120050003        : KO    KO
 V 278102305000331      : OK    OK
 V 278112005000332      : KO    KO
 V 278112B0500033112    : KO    KO
 V 278112005000331      : OK    OK
 V 278112005000339      : KO    KO
 V 578112005000375      : KO    KO
 V 278132005000363      : KO    KO
 V 278130005000321      : KO    KO
 V 2x8112005000331      : KO    KO
---------
Test Validate_FR::siren
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 423068147            : OK    OK
 V 123456789            : KO    KO
 V 123456782            : OK    OK
 V 422260208            : OK    OK
 V 12345678             : KO    KO
 V 1234567840           : KO    KO
---------
Test Validate_FR::siret
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 42306814700010       : OK    OK
 V 12345678912345       : KO    KO
 V 12345678200010       : OK    OK
 V 42226020800026       : OK    OK
 V 1234567891234x25     : KO    KO
 V 12345678             : KO    KO
 V 1234567840           : KO    KO
---------
Test Validate_FR::postalCode
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 01234                : OK    OK
 V 012345               : KO    KO
 V 0123                 : KO    KO
 V 00234                : KO    KO
 V 99234                : OK    OK
 V 2A234                : KO    KO
 V 20234                : OK    OK
 V 0123X                : KO    KO
---------
Test Validate_FR::region
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 12                   : OK    OK
 V 00                   : KO    KO
 V 1                    : KO    KO
 V 100                  : KO    KO
 V 20                   : KO    KO
 V 2A                   : OK    OK
 V 2B                   : OK    OK
 V 2C                   : KO    KO
 V 972                  : OK    OK
 V 979                  : KO    KO
---------
Test Validate_FR::rib
 _ Value                            State Return
 V = validation result is right
 X = validation result is wrong
 V 20041 01003 0175293T024 33     : OK    OK
 V 45499 91289 01697111 65        : OK    OK
 V 20041 01003 0175293T024 34     : KO    KO
 V 45499 91289 01697111 5         : KO    KO
 V 12345 12345 12345678901 46     : OK    OK
 V 1234 5123451 2345678901 46     : KO    KO
 V 12345 12345 12345678901 47     : KO    KO
 V 12345 12345 1234E67H901 46     : OK    OK
 V 12345 12345 1234E67B901 46     : KO    KO
 V 12345 12345 VALIDATEURS 01     : OK    OK
 V 12345 12345 1234E67H901 45     : KO    KO
 V 12345 12345 VALIDATEURS 00     : KO    KO
 V 12345 12345 51394135492 01     : OK    OK
 V 11111 22222 33333333333 91     : OK    OK
 V 11111 22222 33333333298 02     : OK    OK
 V 11111 22222 33333333298 99     : OK    OK
... SUCCESS
