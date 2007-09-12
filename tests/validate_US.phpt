--TEST--
validate_US.phpt: Unit tests for United State Validation.
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('KO', 'OK');
$symbol = array('!X!', ' V ');

include (dirname(__FILE__).'/validate_functions.inc');

if (is_file(dirname(__FILE__) . '/../Validate/US.php')) {
    require_once dirname(__FILE__) . '/../Validate/US.php';
} else {
    require 'Validate/US.php';
}

echo "Test Validate_US\n";
echo "****************\n";

$postalCodes = array(
        /* some test-cases from the original of the postcode-check */
        '48103' => 'OK',
        '48103-6565' => 'OK',
        '48103 6565' => 'OK',
        '1234' => 'KO',
        '3454545' => 'KO',

        /* this ought to be disallowed if the official zip codes have dashes */
        '481036565' => 'KO',

        /* zip codes can start with any digit */
        '00125' => 'OK',
        '12368' => 'OK',
        '22587' => 'OK',
        '36914' => 'OK',
        '56412' => 'OK',
        '68795' => 'OK',
        '71142' => 'OK',
        '85941' => 'OK',
        '90125' => 'OK',

        /* must have five or nine digits */
        '9065' => 'KO',
        '54268-1' => 'KO',
        '54-2681' => 'KO',
        '6154166' => 'KO',
        '10275776' => 'KO',
        '10275-776' => 'KO',
        '1235a' => 'KO',
        'foo' => 'KO',
        'QN55 1PT' => 'KO',
);

$phonenumbersDoNotRequireAreaCode  = array(
                /* test allowed seven digit numbers */
                '875-0987' => 'OK',
                '875 0987' => 'OK',
                '8750987' => 'OK',
                '1750987' => 'KO',
                '0750987' => 'KO',
                '875098a' => 'KO',
                '8dy0985' => 'KO',

                /* test ten digit numbers without a required area code */
                '(467) 875-0987' => 'OK',
                '(467)-875-0987' => 'OK',
                '(467)875-0987' => 'OK',
                '(467) 875 0987' => 'OK',
                '(467)-875 0987' => 'OK',
                '(467)875 0987' => 'OK',
                '(467) 8750987' => 'OK',
                '(467)-8750987' => 'OK',
                '(467)8750987' => 'OK',
                '467-875-0987' => 'OK',
                '4678750987' => 'OK',
                '467 875 0987' => 'OK',
                '267 471-0967' => 'OK',
                '267-471-0967' => 'OK',
                '267471-0967' => 'OK',
                '419 285 9377' => 'OK',
                '419-285 9377' => 'OK',
                '419285 9377' => 'OK',
                '419 2859377' => 'OK',
                '267-4710967' => 'OK',
                '4192859377' => 'OK',

                /* test ten digit numbers without a required area code */
                '(167) 175-0987' => 'KO',
                '(467) 075-0987' => 'KO',
                '(467)-awe-0987' => 'KO',
                '(4r4x7)-875-0987' => 'KO',
                '(469875-0987' => 'KO',
                '98 487-0987' => 'KO',
                );
$phonenumbersRequireAreaCode = array(


                /* test allowed seven digit numbers */
                '875-0987' => 'KO',
                '875 0987' => 'KO',
                '8750987' => 'KO',
                '1750987' => 'KO',
                '0750987' => 'KO',
                '875098a' => 'KO',
                '8dy0985' => 'KO',

                /* test ten digit numbers with a required area code */
                '(467) 875-0987' => 'OK',
                '(467)-875-0987' => 'OK',
                '(467)875-0987' => 'OK',
                '(467) 875 0987' => 'OK',
                '(467)-875 0987' => 'OK',
                '(467)875 0987' => 'OK',
                '(467) 8750987' => 'OK',
                '(467)-8750987' => 'OK',
                '(467)8750987' => 'OK',
                '267 471-0967' => 'OK',
                '267-471-0967' => 'OK',
                '267471-0967' => 'OK',
                '419 285 9377' => 'OK',
                '419-285 9377' => 'OK',
                '419285 9377' => 'OK',
                '419 2859377' => 'OK',
                '267-4710967' => 'OK',
                '4192859377' => 'OK',
                '(313) 535-8553' => 'OK',

                /* test ten digit numbers with a required area code */
                '(4a7) 875-0987' => 'KO',
                '(467)-085-0987' => 'KO',
                '(467)87-0987' => 'KO',
                '(46e) t75 0987' => 'KO',
                '(313 535-8553' => 'KO',

                // This should fail, less digit then is needed
                '(123) 456-78' => 'KO',
                '(517) 474-' => 'KO',
);


$regions = array(
                'MT' => 'OK',
                'DC' => 'OK',
                'ILL' => 'KO',
                'il' => 'OK',
                'FLA' => 'KO',
                'NL' => 'KO',
                ); // NOK

$ssns = array(
                // Should work
                '712180565' => 'OK',
                '019880001' => 'OK',
                '019889999' => 'OK',
                '133920565' => 'OK',
                '001020030' => 'OK',
                '097920845' => 'OK',
                '097469490' => 'OK',
                '001-01-3000' => 'OK',
                '001 42 3000' => 'OK',
                '001 44 3000' => 'OK',

                // Should fail
                '001 43 3000' => 'KO',
                '019880000' => 'KO',
                '712194509' => 'KO',
                '019000000' => 'KO',
                '019890000' => 'KO',
                '001713000' => 'KO',
                '133939759' => 'KO',
                '097999490' => 'KO',
            );

$errorFound = false;
$errorFound = $errorFound || test_func(array('Validate_US','postalCode'  ), $postalCodes );
$errorFound = $errorFound || test_func(array('Validate_US','region'      ), $regions     );
$errorFound = $errorFound || test_func(array('Validate_US','ssn'         ), $ssns        );
$errorFound = $errorFound || test_func(array('Validate_US','phonenumber' ), $phonenumbersDoNotRequireAreaCode, false);
$errorFound = $errorFound || test_func(array('Validate_US','phonenumber' ), $phonenumbersRequireAreaCode,true );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';


?>
--EXPECT--
Test Validate_US
****************
---------
Test Validate_US::postalCode
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 48103                : OK    OK
 V 48103-6565           : OK    OK
 V 48103 6565           : OK    OK
 V 1234                 : KO    KO
 V 3454545              : KO    KO
 V 481036565            : KO    KO
 V 00125                : OK    OK
 V 12368                : OK    OK
 V 22587                : OK    OK
 V 36914                : OK    OK
 V 56412                : OK    OK
 V 68795                : OK    OK
 V 71142                : OK    OK
 V 85941                : OK    OK
 V 90125                : OK    OK
 V 9065                 : KO    KO
 V 54268-1              : KO    KO
 V 54-2681              : KO    KO
 V 6154166              : KO    KO
 V 10275776             : KO    KO
 V 10275-776            : KO    KO
 V 1235a                : KO    KO
 V foo                  : KO    KO
 V QN55 1PT             : KO    KO
---------
Test Validate_US::region
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V MT                   : OK    OK
 V DC                   : OK    OK
 V ILL                  : KO    KO
 V il                   : OK    OK
 V FLA                  : KO    KO
 V NL                   : KO    KO
---------
Test Validate_US::ssn
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 712180565            : OK    OK
 V 019880001            : OK    OK
 V 019889999            : OK    OK
 V 133920565            : OK    OK
 V 001020030            : OK    OK
 V 097920845            : OK    OK
 V 097469490            : OK    OK
 V 001-01-3000          : OK    OK
 V 001 42 3000          : OK    OK
 V 001 44 3000          : OK    OK
 V 001 43 3000          : KO    KO
 V 019880000            : KO    KO
 V 712194509            : KO    KO
 V 019000000            : KO    KO
 V 019890000            : KO    KO
 V 001713000            : KO    KO
 V 133939759            : KO    KO
 V 097999490            : KO    KO
---------
Test Validate_US::phonenumber
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 875-0987             : OK    OK
 V 875 0987             : OK    OK
 V 8750987              : OK    OK
 V 1750987              : KO    KO
 V 0750987              : KO    KO
 V 875098a              : KO    KO
 V 8dy0985              : KO    KO
!X!(467) 875-0987       : OK    KO
!X!(467)-875-0987       : OK    KO
!X!(467)875-0987        : OK    KO
!X!(467) 875 0987       : OK    KO
!X!(467)-875 0987       : OK    KO
!X!(467)875 0987        : OK    KO
!X!(467) 8750987        : OK    KO
!X!(467)-8750987        : OK    KO
!X!(467)8750987         : OK    KO
!X!467-875-0987         : OK    KO
!X!4678750987           : OK    KO
!X!467 875 0987         : OK    KO
!X!267 471-0967         : OK    KO
!X!267-471-0967         : OK    KO
!X!267471-0967          : OK    KO
!X!419 285 9377         : OK    KO
!X!419-285 9377         : OK    KO
!X!419285 9377          : OK    KO
!X!419 2859377          : OK    KO
!X!267-4710967          : OK    KO
!X!4192859377           : OK    KO
 V (167) 175-0987       : KO    KO
 V (467) 075-0987       : KO    KO
 V (467)-awe-0987       : KO    KO
 V (4r4x7)-875-0987     : KO    KO
 V (469875-0987         : KO    KO
 V 98 487-0987          : KO    KO
---------
Test Validate_US::phonenumber
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V 875-0987             : KO    KO
 V 875 0987             : KO    KO
 V 8750987              : KO    KO
 V 1750987              : KO    KO
 V 0750987              : KO    KO
 V 875098a              : KO    KO
 V 8dy0985              : KO    KO
 V (467) 875-0987       : OK    OK
 V (467)-875-0987       : OK    OK
 V (467)875-0987        : OK    OK
 V (467) 875 0987       : OK    OK
 V (467)-875 0987       : OK    OK
 V (467)875 0987        : OK    OK
 V (467) 8750987        : OK    OK
 V (467)-8750987        : OK    OK
 V (467)8750987         : OK    OK
 V 267 471-0967         : OK    OK
 V 267-471-0967         : OK    OK
 V 267471-0967          : OK    OK
 V 419 285 9377         : OK    OK
 V 419-285 9377         : OK    OK
 V 419285 9377          : OK    OK
 V 419 2859377          : OK    OK
 V 267-4710967          : OK    OK
 V 4192859377           : OK    OK
 V (313) 535-8553       : OK    OK
 V (4a7) 875-0987       : KO    KO
 V (467)-085-0987       : KO    KO
 V (467)87-0987         : KO    KO
 V (46e) t75 0987       : KO    KO
 V (313 535-8553        : KO    KO
 V (123) 456-78         : KO    KO
 V (517) 474-           : KO    KO
... SUCCESS
