--TEST--
validate_DK.phpt: Unit tests for 'Validate/DK.php'
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate/DK.php';

echo "Test Validate_DK\n";
echo "****************\n";

$postalCodes = array(
                '8250', // OK
                'DK-8250', // OK
                '250', // NOK
                '250a', // NOK
                'DK-250', // NOK
                'Da-8250', // NOK
                'DK-250a'); // NOK

$phonenumbers = array(
                '21021212', // OK
                '2121212'); // NOK
				
$ssns = array(
				array(
					'010192-1212',
					false // no gender (is female)
				), // OK
				array(
					'211277-8317',
					false // no gender (is male)
				), // OK
				array(
					'211277-8317',
					'M' // is male?
				), // OK
				array(
					'010192-1212',
					'F' // is female?
				), // OK
				array(
					'010192-1212',
					'M' // is male?
				), // NOK !(is male)
				array(
					'01a19.-1212',
					false // no gender (wrong format)
				), // NOK !(wrong format)
				array(
					'no-way',
					false // no gender (wrong format)
				), // NOK !(wrong format)
				array(
					'010192-1211',
					false // no gender
				), // OK (cipher does not match)
				array(
					'012092-1211',
					false // no gender
				) // NOK (Wrong format)
			);

$carregs = array(
                'XC 21 261', // OK
                'DO 216'); // NOK

echo "Test postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_DK::postalCode($postalCode)]."\n";
}

echo "Test ssn\n";
foreach ($ssns as $ssn) {
    echo "{$ssn[0]}, {$ssn[1]}: ".$noYes[Validate_DK::ssn($ssn[0], $ssn[1])]."\n";
}

echo "Test phonenumber\n";
foreach ($phonenumbers as $phonenumber) {
    echo "{$phonenumber}: ".$noYes[Validate_DK::phoneNumber($phonenumber)]."\n";
}

echo "Test carreg\n";
foreach ($carregs as $carreg) {
    echo "{$carreg}: ".$noYes[Validate_DK::carReg($carreg)]."\n";
}
?>
--EXPECT--
Test Validate_DK
****************
Test postalCode
8250: YES
DK-8250: YES
250: NO
250a: NO
DK-250: NO
Da-8250: NO
DK-250a: NO
Test ssn
010192-1212, : YES
211277-8317, : YES
211277-8317, M: YES
010192-1212, F: YES
010192-1212, M: NO
01a19.-1212, : NO
no-way, : NO
010192-1211, : YES
010120-1211, : NO
Test phonenumber
21021212: YES
2121212: NO
Test carreg
XC 21 261: YES
DO 216: NO