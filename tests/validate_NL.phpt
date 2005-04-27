--TEST--
validate_NL.phpt: Unit tests for require_once( "Validate/NL.php" )
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require_once( "Validate/NL.php" );

echo "Test Validate_NL\n";
echo "****************\n";

$postalCodes = array(
        "1234 AB", // OK
        "1234 ab", // OK
        "1234AB", // OK
        "1234ab", // OK
        "1234aB", // OK
        "123456", // NOK
        "1234", // NOK
        "AB1234", // NOK
        "aB12 34" // NOK
);

$phoneNumbers = array(
        array("0612345678"    , VALIDATE_NL_PHONENUMBER_TYPE_MOBILE), // OK
        array("0031612345678" , VALIDATE_NL_PHONENUMBER_TYPE_MOBILE), // OK
        array("+31612345678"  , VALIDATE_NL_PHONENUMBER_TYPE_MOBILE), // OK
        array("0101234567"    , VALIDATE_NL_PHONENUMBER_TYPE_NORMAL), // OK
        array("+31101234567"  , VALIDATE_NL_PHONENUMBER_TYPE_NORMAL), // OK
        array("0031101234567" , VALIDATE_NL_PHONENUMBER_TYPE_NORMAL), // OK
        array("0612345678"    , VALIDATE_NL_PHONENUMBER_TYPE_ANY), // OK
        array("+31101234567"  , VALIDATE_NL_PHONENUMBER_TYPE_ANY), // OK
        array("+31101234567"  , VALIDATE_NL_PHONENUMBER_TYPE_MOBILE), // NOK
        array("+0031612345678", VALIDATE_NL_PHONENUMBER_TYPE_NORMAL) // NOK
);

$SSNs = array(
        "12345678", // NOK
        "1234567890", // NOK
        "123456789" // OK
);

$bankAccountNumbers = array(
        "640000231", // OK
        "640400231" // NOK
);

echo "Test postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_NL::postalCode($postalCode)]."\n";
}

echo "Test phoneNumber\n";
foreach ($phoneNumbers as $phoneNumber) {
    echo "{$phoneNumber[0]} ".($phoneNumber[1]? "(10)" : "(7)").": ".
        $noYes[Validate_NL::phoneNumber($phoneNumber[0], $phoneNumber[1])]."\n";
}

echo "Test SSN\n";
foreach ($SSNs as $SSN) {
    echo "{$SSN}: ".$noYes[Validate_NL::SSN($SSN)]."\n";
}

echo "Test bankAccountNumber\n";
foreach ($bankAccountNumbers as $bankAccountNumber) {
    echo "{$bankAccountNumber}: ".$noYes[Validate_NL::bankAccountNumber($bankAccountNumber)]."\n";
}
?>
--EXPECT--
Test Validate_NL
****************
Test postalCode
1234 AB: YES
1234 ab: YES
1234AB: YES
1234ab: YES
1234aB: YES
123456: NO
1234: NO
AB1234: NO
aB12 34: NO
Test phoneNumber
0612345678 (10): YES
0031612345678 (10): YES
+31612345678 (10): YES
0101234567 (10): YES
+31101234567 (10): YES
0031101234567 (10): YES
0612345678 (7): YES
+31101234567 (7): YES
+31101234567 (10): NO
+0031612345678 (10): NO
Test SSN
12345678: NO
1234567890: NO
123456789: YES
Test bankAccountNumber
640000231: YES
640400231: NO
