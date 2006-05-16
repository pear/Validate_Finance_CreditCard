--TEST--
Validate_IS::phoneNumber
--FILE--
<?php
    error_reporting(E_ALL & ~E_STRICT);
    require_once "Validate/IS.php";

    $phoneNumbers = array(
        5642240             => true,
        "+354 664 22 40"    => true,
        "00354 464 22 40"   => true,
        "00 354 864-22 40"  => true,
        "54-234-56"         => true,
        "+00 354 564 22 40" => false,
        "+354 1234567"      => false,
        1234567             => false,
        87654321            => false 
    );

    foreach($phoneNumbers as $number => $result) {
        printf("%-20s: %s\n", $number, (int)Validate_IS::phoneNumber($number));
    }
?>
--EXPECT--
5642240             : 1
+354 664 22 40      : 1
00354 464 22 40     : 1
00 354 864-22 40    : 1
54-234-56           : 1
+00 354 564 22 40   : 0
+354 1234567        : 0
1234567             : 0
87654321            : 0
