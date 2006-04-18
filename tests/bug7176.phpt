--TEST--
#7389, email RFC 822 
--FILE--
<?php
require_once 'Validate.php';
if (validate::email('Alfred Neuman <Neuman@BBN-TENEXA>')) {
	echo "Ok\n";
}
if (validate::email('"George, Ted" <Shared@Group.Arpanet>')) {
	echo "Ok\n";
}
if (validate::email('Wilt . (the  Stilt) Chamberlain@NBA.US')) {
	echo "Ok\n";
}
?>
--EXPECT--
Ok
Ok
Ok
