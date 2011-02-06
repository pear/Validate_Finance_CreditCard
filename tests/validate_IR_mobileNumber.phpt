--TEST--
mobileNumber.phpt: Unit tests for
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate/IR.php';

echo "Test mobileNumber\n";

$strings = array(
		'989123334444', //OK	
		'00989353334444', //OK	
		'+989363334444', //OK	
		'+98 9373334444', //OK	
		'(+98) 9383334444', //OK	
		'+98-9323334444', //OK	
				
		'teststring', //NOK
		'999123334444', //NOK
		'+999353334444', //NOK
		'00999363334444', //NOK
		'000989373334444', //NOK
		'+00989383334444', //NOK
		'+0989323334444', //NOK
    );

list($version) = explode(".", phpversion(), 2);
foreach ($strings as $string) {
	echo "{$string}: ";
    if ((int)$version > 4) {
		try {
			echo $noYes[Validate_IR::mobileNumber($string)]."\n";
		} catch (Exception $e) {
			echo $e->getMessage()."\n";
		}
	} else {
		echo $noYes[Validate_IR::mobileNumber($string)]."\n";
	}
}
?>
--EXPECT--
Test mobileNumber
989123334444: YES
00989353334444: YES
+989363334444: YES
+98 9373334444: YES
(+98) 9383334444: YES
+98-9323334444: YES
teststring: NO
999123334444: NO
+999353334444: NO
00999363334444: NO
000989373334444: NO
+00989383334444: NO
+0989323334444: NO
