--TEST--
ssn.phpt: Unit tests for
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate/IR.php';

echo "Test ssn\n";

$strings = array(
		'9876543210', //OK
		'1234567891', //OK
		'0324354657', //OK

		'teststring', //NOK
		'1234567890', //NOK
		'3333333333', //NOK
		'0324354654', //NOK
		'12345' //NOK
    );

list($version) = explode(".", phpversion(), 2);
foreach ($strings as $string) {
	echo "{$string}: ";
    if ((int)$version > 4) {
		try {
			echo $noYes[Validate_IR::ssn($string)]."\n";
		} catch (Exception $e) {
			echo $e->getMessage()."\n";
		}
	} else {
		echo $noYes[Validate_IR::ssn($string)]."\n";
	}
}
?>
--EXPECT--
Test ssn
9876543210: YES
1234567891: YES
0324354657: YES
teststring: NO
1234567890: NO
3333333333: NO
0324354654: NO
12345: NO
