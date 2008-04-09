--TEST--
Bug #13633  new mobile operator/prefix in Ireland (Tesco)
--FILE--
<?php
require_once 'Validate/IE.php';
if (Validate_IE::phoneNumber("089 1111111")) {
	echo "OK\n";
}
?>
--EXPECT--
OK
