--TEST--
numeric.phpt: Unit tests for
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/IR.php')) {
    require_once dirname(__FILE__) . '/../Validate/IR.php';
} else {
    require_once 'Validate/IR.php';
}

echo "Test numeric\n";

$strings = array(
		'۱۲۳۴۵۶۷۸۹۰', //OK	
		
		'teststring', //NOK
		'1234567890', //NOK
		'١٢٣٤٥٦٧٨٩٠', //NOK
    );

list($version) = explode(".", phpversion(), 2);
foreach ($strings as $string) {
	echo "{$string}: ";
    if ((int)$version > 4) {
		try {
			echo $noYes[Validate_IR::numeric($string)]."\n";
		} catch (Exception $e) {
			echo $e->getMessage()."\n";
		}
	} else {
		echo $noYes[Validate_IR::numeric($string)]."\n";
	}
}
?>
--EXPECT--
Test numeric
۱۲۳۴۵۶۷۸۹۰: YES
teststring: NO
1234567890: NO
١٢٣٤٥٦٧٨٩٠: NO
