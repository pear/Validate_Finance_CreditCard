--TEST--
validate_US.phpt: Unit tests for 
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate/US.php';

echo "Test Validate_US\n";
echo "****************\n";

$postalCodes = array(
        /* some test-cases from the original of the postcode-check */
        '48103', // OK
        '48103-6565', // OK
        '48103 6565', // OK
        '1234', // NOK
        '3454545', // NOK

        /* this ought to be disallowed if the official zip codes have dashes */
        '481036565', // NOK

        /* zip codes can start with any digit */
        '00125', // OK
        '12368', // OK
        '22587', // OK
        '36914', // OK
        '56412', // OK
        '68795', // OK
        '71142', // OK
        '85941', // OK
        '90125', // OK

        /* must have five or nine digits */
        '9065', // NOK
        '54268-1', // NOK
        '54-2681', // NOK
        '6154166', // NOK
        '10275776', // NOK
        '10275-776', // NOK
        '1235a', // NOK
        'foo', // NOK
        'QN55 1PT' // NOK
);

$phonenumbers = array(
                /* test allowed seven digit numbers */
                array('875-0987', false), // OK
                array('875 0987', false), // OK
                array('8750987', false), // OK
                array('1750987', false), // NOK
                array('0750987', false), // NOK
                array('875098a', false), // NOK
                array('8dy0985', false), // NOK

                /* test allowed seven digit numbers */
                array('875-0987', true), // NOK
                array('875 0987', true), // NOK
                array('8750987', true), // NOK
                array('1750987', true), // NOK
                array('0750987', true), // NOK
                array('875098a', true), // NOK
                array('8dy0985', true), // NOK

                /* test ten digit numbers without a required area code */
                array('(467) 875-0987', false), // OK
                array('(467)-875-0987', false), // OK
                array('(467)875-0987', false), // OK
                array('(467) 875 0987', false), // OK
                array('(467)-875 0987', false), // OK
                array('(467)875 0987', false), // OK
                array('(467) 8750987', false), // OK
                array('(467)-8750987', false), // OK
                array('(467)8750987', false), // OK
                array('267 471-0967', false), // OK
                array('267-471-0967', false), // OK
                array('267471-0967', false), // OK
                array('419 285 9377', false), // OK
                array('419-285 9377', false), // OK
                array('419285 9377', false), // OK
                array('419 2859377', false), // OK
                array('267-4710967', false), // OK
                array('4192859377', false), // OK

                /* test ten digit numbers with a required area code */
                array('(467) 875-0987', true), // OK
                array('(467)-875-0987', true), // OK
                array('(467)875-0987', true), // OK
                array('(467) 875 0987', true), // OK
                array('(467)-875 0987', true), // OK
                array('(467)875 0987', true), // OK
                array('(467) 8750987', true), // OK
                array('(467)-8750987', true), // OK
                array('(467)8750987', true), // OK
                array('267 471-0967', true), // OK
                array('267-471-0967', true), // OK
                array('267471-0967', true), // OK
                array('419 285 9377', true), // OK
                array('419-285 9377', true), // OK
                array('419285 9377', true), // OK
                array('419 2859377', true), // OK
                array('267-4710967', true), // OK
                array('4192859377', true), // OK
                array('(313) 535-8553', true), // OK

                /* test ten digit numbers without a required area code */
                array('(167) 175-0987', false), // NOK
                array('(467) 075-0987', false), // NOK
                array('(467)-awe-0987', false), // NOK
                array('(4r4x7)-875-0987', false), // NOK
                array('(469875-0987', false), // NOK
                array('98 487-0987', false), // NOK

                /* test ten digit numbers with a required area code */
                array('(4a7) 875-0987', true), // NOK
                array('(467)-085-0987', true), // NOK
                array('(467)87-0987', true), // NOK
                array('(46e) t75 0987', true), // NOK
                
                // This should fail, less digit then is needed
                array('(123) 456-78', true), // NOK
                array('(517) 474-', true), // NOK
            );

$regions = array(
                'MT', // OK
                'DC', // OK
                'ILL', // NOK
                'il', // OK
                'FLA', // NOK
                'NL'); // NOK

$ssns = array(
                // Should work
                '712180565', // OK
                '019880001', // OK
                '019889999', // OK
                '133920565', // OK
                '001020030', // OK
                '097920845', // OK
                '097469490', // OK
                '001-01-3000', // OK
                '001 42 3000', // OK
                '001 44 3000', // OK
           
                // Should fail
                '001 43 3000', // NOK
                '019880000', // NOK
                '712194509', // NOK
                '019000000', // NOK
                '019890000', // NOK
                '001713000', // NOK
                '133939759', // NOK
                '097999490', // NOK
            );

echo "Test postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_US::postalCode($postalCode)]."\n";
}

echo "Test phonenumber\n";
foreach ($phonenumbers as $phonenumber) {
    echo "{$phonenumber[0]} ".($phonenumber[1]? "(10)" : "(7)").": ".
        $noYes[Validate_US::phonenumber($phonenumber[0], $phonenumber[1])]."\n";
}

echo "Test region\n";
foreach ($regions as $region) {
    echo "{$region}: ".$noYes[Validate_US::region($region)]."\n";
}

echo "Test ssn\n";
foreach ($ssns as $ssn) {
    echo "{$ssn}: ".$noYes[Validate_US::ssn($ssn)]."\n";
}
?>
--EXPECT--
Test Validate_US
****************
Test postalCode
48103: YES
48103-6565: YES
48103 6565: YES
1234: NO
3454545: NO
481036565: NO
00125: YES
12368: YES
22587: YES
36914: YES
56412: YES
68795: YES
71142: YES
85941: YES
90125: YES
9065: NO
54268-1: NO
54-2681: NO
6154166: NO
10275776: NO
10275-776: NO
1235a: NO
foo: NO
QN55 1PT: NO
Test phonenumber
875-0987 (7): YES
875 0987 (7): YES
8750987 (7): YES
1750987 (7): NO
0750987 (7): NO
875098a (7): NO
8dy0985 (7): NO
875-0987 (10): NO
875 0987 (10): NO
8750987 (10): NO
1750987 (10): NO
0750987 (10): NO
875098a (10): NO
8dy0985 (10): NO
(467) 875-0987 (7): YES
(467)-875-0987 (7): YES
(467)875-0987 (7): YES
(467) 875 0987 (7): YES
(467)-875 0987 (7): YES
(467)875 0987 (7): YES
(467) 8750987 (7): YES
(467)-8750987 (7): YES
(467)8750987 (7): YES
267 471-0967 (7): YES
267-471-0967 (7): YES
267471-0967 (7): YES
419 285 9377 (7): YES
419-285 9377 (7): YES
419285 9377 (7): YES
419 2859377 (7): YES
267-4710967 (7): YES
4192859377 (7): YES
(467) 875-0987 (10): YES
(467)-875-0987 (10): YES
(467)875-0987 (10): YES
(467) 875 0987 (10): YES
(467)-875 0987 (10): YES
(467)875 0987 (10): YES
(467) 8750987 (10): YES
(467)-8750987 (10): YES
(467)8750987 (10): YES
267 471-0967 (10): YES
267-471-0967 (10): YES
267471-0967 (10): YES
419 285 9377 (10): YES
419-285 9377 (10): YES
419285 9377 (10): YES
419 2859377 (10): YES
267-4710967 (10): YES
4192859377 (10): YES
(313) 535-8553 (10): YES
(167) 175-0987 (7): NO
(467) 075-0987 (7): NO
(467)-awe-0987 (7): NO
(4r4x7)-875-0987 (7): NO
(469875-0987 (7): NO
98 487-0987 (7): NO
(4a7) 875-0987 (10): NO
(467)-085-0987 (10): NO
(467)87-0987 (10): NO
(46e) t75 0987 (10): NO
(123) 456-78 (10): NO
(517) 474- (10): NO
Test region
MT: YES
DC: YES
ILL: NO
il: YES
FLA: NO
NL: NO
Test ssn
712180565: YES
019880001: YES
019889999: YES
133920565: YES
001020030: YES
097920845: YES
097469490: YES
001-01-3000: YES
001 42 3000: YES
001 44 3000: YES
001 43 3000: NO
019880000: NO
712194509: NO
019000000: NO
019890000: NO
001713000: NO
133939759: NO
097999490: NO
