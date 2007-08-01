--TEST--
validate_IE_post_code.phpt: Unit tests for postalCode method 'Validate/IE.php'

--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/IE.php')) {
    require_once dirname(__FILE__) . '/../Validate/IE.php';
    $postcodes_dir = dirname(__FILE__) . '/../data';
} else {
    require_once 'Validate/IE.php';
    $postcodes_dir = null;
}

echo "Test Validate_IE\n";
echo "****************\n";

//test passport
$codes = array(
'D1', //OK
'D6W', //OK
'D01', //NOK - shouldn't be a zero after D
'D100' //NOK - too long
);
echo "\nTest Postal Codes\n";
foreach ($codes as $code) {
    echo "{$code}: ".$noYes[Validate_IE::postalCode($code, $postcodes_dir)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_IE
****************

Test Postal Codes
D1: YES
D6W: YES
D01: NO
D100: NO
