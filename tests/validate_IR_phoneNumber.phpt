--TEST--
mobileNumber.phpt: Unit tests for
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate/IR.php';

echo "Test phoneNumber\n";

$strings = array(
		'982133334444', //OK	
		'00982133334444', //OK	
		'+982133334444', //OK	
		'+98 2133334444', //OK	
		'+98 21 33334444', //OK	
		'+98-21-33334444', //OK	
		'(+98) (21) (33334444)', //OK	
		'02133334444', //OK	
		'021 33334444', //OK	
		'021 33334444', //OK	
		'(021) (118)', //OK	
		'0411 3334444', //OK	
		'(0411) (3334444)', //OK
						
		'teststring', //NOK
		'992133334444', //NOK
		'+992133334444', //NOK
		'00992133334444', //NOK
		'000982133334444', //NOK
		'+00982133334444', //NOK
		'+0982133334444', //NOK
		'+98/21/33334444', //NOK
		'+02133334444', //NOK
		'021 22' //NOK
    );

list($version) = explode(".", phpversion(), 2);
foreach ($strings as $string) {
	echo "{$string}: ";
    if ((int)$version > 4) {
		try {
			echo $noYes[Validate_IR::phoneNumber($string)]."\n";
		} catch (Exception $e) {
			echo $e->getMessage()."\n";
		}
	} else {
		echo $noYes[Validate_IR::phoneNumber($string)]."\n";
	}
}
?>
--EXPECT--
Test phoneNumber
982133334444: YES
00982133334444: YES
+982133334444: YES
+98 2133334444: YES
+98 21 33334444: YES
+98-21-33334444: YES
(+98) (21) (33334444): YES
02133334444: YES
021 33334444: YES
021 33334444: YES
(021) (118): YES
0411 3334444: YES
(0411) (3334444): YES
teststring: NO
992133334444: NO
+992133334444: NO
00992133334444: NO
000982133334444: NO
+00982133334444: NO
+0982133334444: NO
+98/21/33334444: NO
+02133334444: NO
021 22: NO
