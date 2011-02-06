--TEST--
alphaNumeric.phpt: Unit tests for
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate/IR.php';

echo "Test alphaNumeric\n";

$strings = array(
		'آزمایش ۱۲۳۴۵۶۷۸۹۰', //OK
		'آزمایش 1234567890', //OK
		'هِمّت بُلَند دار کِه مَردانِ روزگار  اَز همّتِ بُلَند به جایی رسیده‌اَند', //OK
		'﷼', //OK
		
		'teststring', //NOK
		'test1234567890', //NOK
		'test آزمایش', //NOK
    );

list($version) = explode(".", phpversion(), 2);
foreach ($strings as $string) {
	echo "{$string}: ";
    if ((int)$version > 4) {
		try {
			echo $noYes[Validate_IR::alphaNumeric($string)]."\n";
		} catch (Exception $e) {
			echo $e->getMessage()."\n";
		}
	} else {
		echo $noYes[Validate_IR::alphaNumeric($string)]."\n";
	}
}
?>
--EXPECT--
Test alphaNumeric
آزمایش ۱۲۳۴۵۶۷۸۹۰: YES
آزمایش 1234567890: YES
هِمّت بُلَند دار کِه مَردانِ روزگار  اَز همّتِ بُلَند به جایی رسیده‌اَند: YES
﷼: YES
teststring: NO
test1234567890: NO
test آزمایش: NO
