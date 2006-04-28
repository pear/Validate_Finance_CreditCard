--TEST--
validate_IS.phpt: Unit tests for 'Validate/IS.php'
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate/IS.php';

$validate = new Validate_IS;
echo "Test Validate_IS\n";
echo "****************\n";

$postalCodes = array(
        /* few of the "biggest citys" postal codes */
        101, // OK
        170, // OK
        200, // OK
        210, // OK
        220, // OK
        230, // OK
        300, // OK
        400, // OK
        500, // OK
        600, // OK
        700, // OK
        800, // OK
        900, // OK

        /* random numbers, should all fail */
        100, // NOK
        120, // NOK
        140, // NOK
        205, // NOK
        305, // NOK
        472, // NOK
        903, // NOK

        /* absurd codes */
        99, // NOK
        1000, // NOK
        "1OO", // NOK
        "abc", // NOK
);

$telNumbers = array(
                5642240, // OK
                "+354 664 22 40", // OK
                "00354 464 22 40", // OK
                "00 354 864-22 40", // OK
                "+00 354 564 22 40", // NOK
                "+354 1234567", // NOK
                1234567, // NOK
                87654321, // NOK
                '54-234-56'); // OK

echo "Test postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[$validate->postalCode($postalCode)]."\n";
}

echo "\nTest postalCode strong\n";
foreach($postalCodes as $postalCode) {
    printf("%s: %s\n", $postalCode, $noYes[$validate->postalCode($postalCode, true)]);
}

echo "\nTest phoneNumbers\n";
foreach ($telNumbers as $tel) {
    echo "{$tel}: ".$noYes[$validate->phoneNumber($tel)]."\n";
}
?>
--EXPECT--
Test Validate_IS
****************
Test postalCode
101: YES
170: YES
200: YES
210: YES
220: YES
230: YES
300: YES
400: YES
500: YES
600: YES
700: YES
800: YES
900: YES
100: NO
120: NO
140: NO
205: NO
305: NO
472: NO
903: NO
99: NO
1000: NO
1OO: NO
abc: NO

Test postalCode strong
101: YES
170: YES
200: YES
210: YES
220: YES
230: YES
300: YES
400: YES
500: YES
600: YES
700: YES
800: YES
900: YES
100: NO
120: NO
140: NO
205: NO
305: NO
472: NO
903: NO
99: NO
1000: NO
1OO: NO
abc: NO

Test phoneNumbers
5642240: YES
+354 664 22 40: YES
00354 464 22 40: YES
00 354 864-22 40: YES
+00 354 564 22 40: NO
+354 1234567: NO
1234567: NO
87654321: NO
54-234-56: YES
