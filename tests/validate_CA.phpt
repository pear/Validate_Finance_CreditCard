--TEST--
validate_CA.phpt: Unit tests for
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');

if (is_file(dirname(__FILE__) . '/../Validate/CA.php')) {
    require_once dirname(__FILE__) . '/../Validate/CA.php';
} else {
    require 'Validate/CA.php';
}

echo "Test Validate_CA\n";
echo "****************\n";

$postalCodes = array(
                '48103', // NOK
                'A1B 3B4', // OK
                '1A2 B3C', // NOK
                'a1b 3b4', // OK
                'A2B3B4', // OK
                'A2B-3B4', // OK
                '10B 1C2', // NOK
                '10B1C2', // NOK
                '345 545', // NOK
                '345545', // NOK

                /* postal codes can only start with certain letters */
                'A0A 8Z8', // OK
                'B1A 8Y8', // OK
                'C2A 8X8', // OK
                'D3A  8W8', // NOK
                'E3A  8V8', // OK
                'F3A  8U8', // NOK
                'G4A-8T8', // OK
                'H5A 8S8', // OK
                'I5A 8R8', // NOK
                'J6A 8Q8', // OK
                'K7A 8P8', // OK
                'L8A 8P8', // OK
                'M9A 8N8', // OK
                'N0A 8M8', // OK
                'O1A 8L8', // NOK
                'P2A 8K8', // OK
                'Q3A 8J8', // NOK
                'R4A 8J8', // OK
                'S5A 8H8', // OK
                'T6A 8G8', // OK
                'U7A 8F8', // NOK
                'V8A 8E8', // OK
                'W9A 8D8', // NOK
                'X0A 8C8', // OK
                'Y1A 8B8', // OK
                'Z2A 8A8', // NOK

                /* I and O never occur */
                'Y1I 8B8', // NOK
                'Y1B 8I8', // NOK
                'Y1O 8B8', // NOK
                'Y1B 8O8', // NOK

                /* must have six "digits" */
                '9A065', // NOK
                '9A0 6B', // NOK
                '9A0  6B', // NOK
                '9065', // NOK
                'A2B-4C', // NOK
                'B2A--4C', // NOK
                'A2B 3B45', // NOK
                'A2B 3B4C', // NOK
                'A2B3C4E', // NOK
                'ABCEFG', // NOK
                '1235a', // NOK
                'foo', // NOK
                'QN55 1PT'); // NOK

$phonenumbers = array(
                /* test allowed seven digit numbers (area code not required) */
                array('875-0987', false), // OK
                array('875 0987', false), // OK
                array('8750987', false), // OK
                array('1750987', false), // NOK
                array('0750987', false), // NOK
                array('875098a', false), // NOK
                array('8dy0985', false), // NOK

                /* test allowed seven digit numbers (area code required) */
                array('875-0987', true), // NOK
                array('875 0987', true), // NOK
                array('8750987', true), // NOK
                array('1750987', true), // NOK
                array('0750987', true), // NOK
                array('875098a', true), // NOK
                array('8dy0985', true), // NOK

                /* test ten digit numbers without a required area code */
                array('(467) 875-0987', false),  // OK
                array('(467)-875-0987', false),  // OK
                array('(467)875-0987', false),  // OK
                array('(467) 875 0987', false),  // OK
                array('(467)-875 0987', false),  // OK
                array('(467)875 0987', false),  // OK
                array('(467) 8750987', false),  // OK
                array('(467)-8750987', false),  // OK
                array('(467)8750987', false),  // OK
                array('267 471-0967', false),  // OK
                array('267-471-0967', false),  // OK
                array('267471-0967', false),  // OK
                array('419 285 9377', false),  // OK
                array('419-285 9377', false),  // OK
                array('419285 9377', false),  // OK
                array('419 2859377', false),  // OK
                array('267-4710967', false),  // OK
                array('4192859377', false),  // OK

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
                array('(313 535-8553', true), // NOK

                // This should fail, less digit than needed
                array('(123) 456-78', true), // NOK
                array('(517) 474-', true), // NOK

                /* 1st digit of area code should be 2-9 */
                array('100 555 0100', true), // NOK
                array('200 555 0100', true), // OK
                array('300 555 0100', true), // OK
                array('400 555 0100', true), // OK
                array('500 555 0100', true), // OK
                array('600 555 0100', true), // OK
                array('700 555 0100', true), // OK
                array('800 555 0100', true), // OK
                array('900 555 0100', true), // OK

                /* 2nd digit of area code should be 0-8 */
                array('200 555 0100', true), // OK
                array('210 555 0100', true), // OK
                array('220 555 0100', true), // OK
                array('230 555 0100', true), // OK
                array('240 555 0100', true), // OK
                array('250 555 0100', true), // OK
                array('260 555 0100', true), // OK
                array('270 555 0100', true), // OK
                array('280 555 0100', true), // OK
                array('290 555 0100', true), // NOK

                /* Area codes cannot end in 11 */
                array('111 555 0100', true), // NOK
                array('211 555 0100', true), // NOK
                array('311 555 0100', true), // NOK
                array('411 555 0100', true), // NOK
                array('511 555 0100', true), // NOK
                array('611 555 0100', true), // NOK
                array('711 555 0100', true), // NOK
                array('811 555 0100', true), // NOK
                array('911 555 0100', true), // NOK

                /* "Geographic" exchange codes cannot end in 11 */
                array('222 011 0100', true), // NOK
                array('222 111 0100', true), // NOK
                array('222 211 0100', true), // NOK
                array('222 311 0100', true), // NOK
                array('222 411 0100', true), // NOK
                array('222 511 0100', true), // NOK
                array('222 611 0100', true), // NOK
                array('222 711 0100', true), // NOK
                array('222 811 0100', true), // NOK
                array('222 911 0100', true), // NOK
                array('011 0100', false), // NOK
                array('111 0100', false), // NOK
                array('211 0100', false), // NOK
                array('311 0100', false), // NOK
                array('411 0100', false), // NOK
                array('511 0100', false), // NOK
                array('611 0100', false), // NOK
                array('711 0100', false), // NOK
                array('811 0100', false), // NOK
                array('911 0100', false), // NOK

                /* Toll-free/premium "exchange" codes can end in 11 */
                array('800 211 0100', true), // OK
                array('822 211 0100', true), // OK
                array('833 311 0100', true), // OK
                array('844 411 0100', true), // OK
                array('855 511 0100', true), // OK
                array('866 611 0100', true), // OK
                array('877 711 0100', true), // OK
                array('880 811 0100', true), // OK
                array('881 911 0100', true), // OK
                array('882 211 0100', true), // OK
                array('883 211 0100', true), // OK
                array('884 211 0100', true), // OK
                array('885 311 0100', true), // OK
                array('886 411 0100', true), // OK
                array('887 511 0100', true), // OK
                array('888 611 0100', true), // OK
                array('889 711 0100', true), // OK
                array('900 811 0100', true), // OK

            );

$regions = array(
                'QC', // OK
                'SK', // OK
                'ONT', // NOK
                'mb', // OK
                'XY'); // NOK

$ssns = array(
                // Should work
                '123456782', // OK
                '123/456/782', // OK
                '123 456 782', // OK
                '123-456-782', // OK
                '233345677', // OK

                // Should fail
                '012 345 674', // NOK
                '012345674', // NOK
                '312345671', // NOK
                '831234562', // NOK
                '934345679', // NOK
                '934305673', // NOK
                '9343.5673', // NOK
                '9343O5673', // NOK
                '23A34567B'); // NOK

echo "Test postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_CA::postalCode($postalCode)]."\n";
}

echo "Test phonenumber\n";
foreach ($phonenumbers as $phonenumber) {
    echo "{$phonenumber[0]} ".($phonenumber[1]? "(10)" : "(7)").": ".
        $noYes[Validate_CA::phonenumber($phonenumber[0], $phonenumber[1])]."\n";
}

echo "Test region\n";
foreach ($regions as $region) {
    echo "{$region}: ".$noYes[Validate_CA::region($region)]."\n";
}

echo "Test ssn\n";
foreach ($ssns as $ssn) {
    echo "{$ssn}: ".$noYes[Validate_CA::ssn($ssn)]."\n";
}
?>
--EXPECT--
Test Validate_CA
****************
Test postalCode
48103: NO
A1B 3B4: YES
1A2 B3C: NO
a1b 3b4: YES
A2B3B4: YES
A2B-3B4: YES
10B 1C2: NO
10B1C2: NO
345 545: NO
345545: NO
A0A 8Z8: YES
B1A 8Y8: YES
C2A 8X8: YES
D3A  8W8: NO
E3A  8V8: YES
F3A  8U8: NO
G4A-8T8: YES
H5A 8S8: YES
I5A 8R8: NO
J6A 8Q8: YES
K7A 8P8: YES
L8A 8P8: YES
M9A 8N8: YES
N0A 8M8: YES
O1A 8L8: NO
P2A 8K8: YES
Q3A 8J8: NO
R4A 8J8: YES
S5A 8H8: YES
T6A 8G8: YES
U7A 8F8: NO
V8A 8E8: YES
W9A 8D8: NO
X0A 8C8: YES
Y1A 8B8: YES
Z2A 8A8: NO
Y1I 8B8: NO
Y1B 8I8: NO
Y1O 8B8: NO
Y1B 8O8: NO
9A065: NO
9A0 6B: NO
9A0  6B: NO
9065: NO
A2B-4C: NO
B2A--4C: NO
A2B 3B45: NO
A2B 3B4C: NO
A2B3C4E: NO
ABCEFG: NO
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
(467) 875-0987 (7): NO
(467)-875-0987 (7): NO
(467)875-0987 (7): NO
(467) 875 0987 (7): NO
(467)-875 0987 (7): NO
(467)875 0987 (7): NO
(467) 8750987 (7): NO
(467)-8750987 (7): NO
(467)8750987 (7): NO
267 471-0967 (7): NO
267-471-0967 (7): NO
267471-0967 (7): NO
419 285 9377 (7): NO
419-285 9377 (7): NO
419285 9377 (7): NO
419 2859377 (7): NO
267-4710967 (7): NO
4192859377 (7): NO
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
(313 535-8553 (10): NO
(123) 456-78 (10): NO
(517) 474- (10): NO
100 555 0100 (10): NO
200 555 0100 (10): YES
300 555 0100 (10): YES
400 555 0100 (10): YES
500 555 0100 (10): YES
600 555 0100 (10): YES
700 555 0100 (10): YES
800 555 0100 (10): YES
900 555 0100 (10): YES
200 555 0100 (10): YES
210 555 0100 (10): YES
220 555 0100 (10): YES
230 555 0100 (10): YES
240 555 0100 (10): YES
250 555 0100 (10): YES
260 555 0100 (10): YES
270 555 0100 (10): YES
280 555 0100 (10): YES
290 555 0100 (10): NO
111 555 0100 (10): NO
211 555 0100 (10): NO
311 555 0100 (10): NO
411 555 0100 (10): NO
511 555 0100 (10): NO
611 555 0100 (10): NO
711 555 0100 (10): NO
811 555 0100 (10): NO
911 555 0100 (10): NO
222 011 0100 (10): NO
222 111 0100 (10): NO
222 211 0100 (10): NO
222 311 0100 (10): NO
222 411 0100 (10): NO
222 511 0100 (10): NO
222 611 0100 (10): NO
222 711 0100 (10): NO
222 811 0100 (10): NO
222 911 0100 (10): NO
011 0100 (7): NO
111 0100 (7): NO
211 0100 (7): NO
311 0100 (7): NO
411 0100 (7): NO
511 0100 (7): NO
611 0100 (7): NO
711 0100 (7): NO
811 0100 (7): NO
911 0100 (7): NO
800 211 0100 (10): YES
822 211 0100 (10): YES
833 311 0100 (10): YES
844 411 0100 (10): YES
855 511 0100 (10): YES
866 611 0100 (10): YES
877 711 0100 (10): YES
880 811 0100 (10): YES
881 911 0100 (10): YES
882 211 0100 (10): YES
883 211 0100 (10): YES
884 211 0100 (10): YES
885 311 0100 (10): YES
886 411 0100 (10): YES
887 511 0100 (10): YES
888 611 0100 (10): YES
889 711 0100 (10): YES
900 811 0100 (10): YES
Test region
QC: YES
SK: YES
ONT: NO
mb: YES
XY: NO
Test ssn
123456782: YES
123/456/782: YES
123 456 782: YES
123-456-782: YES
233345677: YES
012 345 674: NO
012345674: NO
312345671: NO
831234562: NO
934345679: NO
934305673: NO
9343.5673: NO
9343O5673: NO
23A34567B: NO
