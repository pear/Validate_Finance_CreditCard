--TEST--
creditCard.phpt: Unit tests for
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate/IR.php';

echo "Test creditCard\n";

$strings = array(
		'1111222233334444', //OK	
		'1111-2222-3333-4444', //OK	
				
		'teststring', //NOK
		'1111', //NOK
		'111-122-223-333-444-4', //NOK
    );

list($version) = explode(".", phpversion(), 2);
foreach ($strings as $string) {
	echo "{$string}: ";
    if ((int)$version > 4) {
		try {
			echo $noYes[Validate_IR::creditCard($string)]."\n";
		} catch (Exception $e) {
			echo $e->getMessage()."\n";
		}
	} else {
		echo $noYes[Validate_IR::creditCard($string)]."\n";
	}
}
?>
--EXPECT--
Test creditCard
1111222233334444: YES
1111-2222-3333-4444: YES
teststring: NO
1111: NO
111-122-223-333-444-4: NO
