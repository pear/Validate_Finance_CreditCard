--TEST--
postalCode.phpt: Unit tests for
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate/IR.php';

echo "Test postalCode\n";

$strings = array(
		'1234567890', //OK

		'teststring', //NOK
		'123456789' //NOK
    );

list($version) = explode(".", phpversion(), 2);
foreach ($strings as $string) {
	echo "{$string}: ";
    if ((int)$version > 4) {
		try {
			echo $noYes[Validate_IR::postalCode($string)]."\n";
		} catch (Exception $e) {
			echo $e->getMessage()."\n";
		}
	} else {
		echo $noYes[Validate_IR::postalCode($string)]."\n";
	}
}
?>
--EXPECT--
Test postalCode
1234567890: YES
teststring: NO
123456789: NO
