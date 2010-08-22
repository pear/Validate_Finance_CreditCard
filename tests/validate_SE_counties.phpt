--TEST--
Test Swedish counties
--FILE--
<?php
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/SE.php')) {
    require_once dirname(__FILE__) . '/../Validate/SE.php';
} else {
    require_once 'Validate/SE.php';
}


$counties = array(
    'XX',//no
    'AB',//ok
	'ab'//ok
);

foreach ($counties as $county) {
    echo "{$county}: "
        . $noYes[
            Validate_SE::county($county)
        ] . "\n";
}
// County O With Return value
echo Validate_SE::county('O', true);
echo "\n";
// County O With Return value
$doesNotExist = Validate_SE::county('NOT_THERE', true);
var_dump($doesNotExist);

--EXPECT--
XX: NO
AB: YES
ab: YES
Västra Götalands län
bool(false)
