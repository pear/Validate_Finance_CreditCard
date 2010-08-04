--TEST--
Test liechtenstein postal codes
--FILE--
<?php
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/LI.php')) {
    require_once dirname(__FILE__) . '/../Validate/LI.php';
} else {
    require_once 'Validate/LI.php';
}


$postalCodes = array(
    1234,//ok
    9238,//ok
    'a123',//no
    '923Z',//no
    123,//no
    12,//no
    1,//no
);

foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: "
        . $noYes[
            Validate_LI::postalCode($postalCode)
        ] . "\n";
}

$postalCodesStrong = array(
    9485,//ok
    9491,//ok
    9499,//no
    9500,//no
    12345,//no
);

echo "strong\n";
foreach ($postalCodesStrong as $postalCode) {
    echo "{$postalCode}: "
        . $noYes[
            Validate_LI::postalCode($postalCode, true)
        ] . "\n";
}
?>
--EXPECT--
1234: YES
9238: YES
a123: NO
923Z: NO
123: NO
12: NO
1: NO
strong
9485: YES
9491: YES
9499: NO
9500: NO
12345: NO
