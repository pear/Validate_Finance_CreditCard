--TEST--
#7389, Codes postaux non pris en compte
--FILE--
<?php
require_once 'Validate/FR.php';
if (validate_FR::postalCode("98001")) {
	echo "#1 Ok\n";
}
if (validate_FR::postalCode("98701")) {
	echo "#2 Ok\n";
}
if (validate_FR::postalCode("98801")) {
	echo "#3 Ok\n";
}
if (validate_FR::region("986")) {
	echo "#4 Ok\n";
}
if (validate_FR::region("987")) {
	echo "#5 Ok\n";
}
if (validate_FR::region("971")) {
	echo "#6 Ok\n";
}
?>
--EXPECT--
#1 Ok
#2 Ok
#3 Ok
#4 Ok
#5 Ok
#6 Ok
