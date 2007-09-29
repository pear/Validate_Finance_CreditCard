--TEST--
validate_UK.phpt: Unit tests for United Kingdom Validation class
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/UK.php')) {
    require_once dirname(__FILE__) . '/../Validate/UK.php';
    require_once dirname(__FILE__) . '/../Validate/UK/carReg.php';
} else {
    require_once 'Validate/UK.php';
}

echo "Test Validate_UK\n";
echo "****************\n";

$postalCodes = array(
        /* some test-cases from the original of the postalCode-check */
        'BS25 1NB', // OK
        'B5 5TF', // OK
        '5Ty6tty', // NOK
        '3454545', // NOK
        'SW1 4RT', // OK
        'SW1A 4RT', // OK
        'BS45678TH', // NOK
        'BF 3RT', // NOK

        /* official examples from the docs */
        'M1 1AA', // OK
        'M60 1NW', // OK
        'CR2 6XH', // OK
        'DN55 1PT', // OK
        'W1A 1HQ', // OK
        'EC1A 1BB', // OK
        'GIR 0AA', // OK

        /* some variations of the official examples which make it non-compliant */
        'V1 1AA', // NOK
        'M6L 1NW', // NOK
        'CJ2 6XH', // NOK
        'QN55 1PT', // NOK
        'W1L 1HQ', // NOK
        'EC1A 1BC', // NOK
        'GIR 1AA', // NOK

        /* additional checks by David Grant (djg), these are valid */
        'CF23 7JN', // OK
        'BS8 4UD', // OK
        'GU19 5AT', // OK
        'CF11 6TA', // OK
        'SW1A 1AA', // OK
        'NW9 0EQ', // OK

        /* additional checks by David Grant (djg), these are invalid */
        'AB12C 1AA', // NOK
        'B12D 3XY', // NOK
        'Q1 5AT', // NOK
        'BI10 4UD' // NOK
);

$ssns = array(
                'JM 40 24 25 C', // OK
                'JM56765F', // NOK
                'JM567645T', // NOK
                'JM567645R', // NOK
                'JM567645D', // OK
                'BG567645D'); // NOK

$sortCodes = array(
                '09-01-24', // OK
                '345676', // NOK
                '0-78-56', // NOK
                '21-68-78', // OK
                'foo21-68-78', // NOK
                '21-68-78bar', // NOK
                '34-234-56'); // NOK

$telNumbers = array(
    '02012345678', // OK
    '020-1234-5678', // OK
    '020 1234 5678', // OK
    '000 1234 5678', // NOK
    '020 1234 56789', // NOK
    '020 1234 567', // NOK
    'foo020 1234 5678', // NOK
    '020 1234 5678bar', // NOK
);

$accountNumbers = array(
    '01234567', // OK
    '012345678', // NOK
    'foo01234567', // NOK
    '01234567bar', // NOK
    'foobar'); // NOK

$drivingLicences = array(
    'ABCDE012345ABCDE', // OK
    'ABCDEE012345ABCDE', // NOK
    'ABCDE012345ABCD', // NOK
    'fooABCDE012345ABCDE', // NOK
    'ABCDE012345ABCDEbar'); // NOK

$carRegistrations = array(
    'A 123 ABC', // OK
    'S7 VEN', // OK
    'ABC 123 A', // OK
    'A1', // OK
    '123 ABC', // NOK
    '11', // NOK
    'AA', // NOK
    'AA 51 ABCDE', // NOK
    '');  // NOK

echo "Test postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_UK::postalCode($postalCode)]."\n";
}

echo "\nTest ssn\n";
foreach ($ssns as $ssn) {
    echo "{$ssn}: ".$noYes[Validate_UK::ssn($ssn)]."\n";
}

echo "\nTest sortCode\n";
foreach ($sortCodes as $sortCode) {
    echo "{$sortCode}: ".$noYes[Validate_UK::sortCode($sortCode)]."\n";
}

echo "\nTest tel\n";
foreach ($telNumbers as $v) {
    echo "{$v}: ".$noYes[Validate_UK::phoneNumber($v)]."\n";
}

echo "\nTest bankAC\n";
foreach ($accountNumbers as $v) {
    echo "{$v}: ".$noYes[Validate_UK::bankAC($v)]."\n";
}

echo "\nTest drive\n";
foreach ($drivingLicences as $v) {
    echo "{$v}: ".$noYes[Validate_UK::drive($v)]."\n";
}


echo "\nCar registrations\n";
foreach ($carRegistrations as $v) {
    echo "{$v}: ".$noYes[Validate_UK::carReg($v)]."\n";
}
?>
--EXPECT--
Test Validate_UK
****************
Test postalCode
BS25 1NB: YES
B5 5TF: YES
5Ty6tty: NO
3454545: NO
SW1 4RT: YES
SW1A 4RT: YES
BS45678TH: NO
BF 3RT: NO
M1 1AA: YES
M60 1NW: YES
CR2 6XH: YES
DN55 1PT: YES
W1A 1HQ: YES
EC1A 1BB: YES
GIR 0AA: YES
V1 1AA: NO
M6L 1NW: NO
CJ2 6XH: NO
QN55 1PT: NO
W1L 1HQ: NO
EC1A 1BC: NO
GIR 1AA: NO
CF23 7JN: YES
BS8 4UD: YES
GU19 5AT: YES
CF11 6TA: YES
SW1A 1AA: YES
NW9 0EQ: YES
AB12C 1AA: NO
B12D 3XY: NO
Q1 5AT: NO
BI10 4UD: NO

Test ssn
JM 40 24 25 C: YES
JM56765F: NO
JM567645T: NO
JM567645R: NO
JM567645D: YES
BG567645D: NO

Test sortCode
09-01-24: YES
345676: NO
0-78-56: NO
21-68-78: YES
foo21-68-78: NO
21-68-78bar: NO
34-234-56: NO

Test tel
02012345678: YES
020-1234-5678: YES
020 1234 5678: YES
000 1234 5678: NO
020 1234 56789: NO
020 1234 567: NO
foo020 1234 5678: NO
020 1234 5678bar: NO

Test bankAC
01234567: YES
012345678: NO
foo01234567: NO
01234567bar: NO
foobar: NO

Test drive
ABCDE012345ABCDE: YES
ABCDEE012345ABCDE: NO
ABCDE012345ABCD: NO
fooABCDE012345ABCDE: NO
ABCDE012345ABCDEbar: NO

Car registrations
A 123 ABC: YES
S7 VEN: YES
ABC 123 A: YES
A1: YES
123 ABC: NO
11: NO
AA: NO
AA 51 ABCDE: NO
: NO
