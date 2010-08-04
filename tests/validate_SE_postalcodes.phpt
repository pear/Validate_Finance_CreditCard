--TEST--
Test swedish postal codes
--FILE--
<?php
$noYes = array('NO', 'YES');
if (is_file(dirname(__FILE__) . '/../Validate/SE.php')) {
    require_once dirname(__FILE__) . '/../Validate/SE.php';
} else {
    require_once 'Validate/SE.php';
}


$postalCodes = array(
    12345,//ok
    92382,//ok
    '923 82',//ok
    '92 382',//no
    'a123',//no
    '923Z3',//no
    1234,//no
    123,//no
    12,//no
    1,//no
);

foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: "
        . $noYes[
            Validate_SE::postalCode($postalCode)
        ] . "\n";
}

--EXPECT--
12345: YES
92382: YES
923 82: YES
92 382: NO
a123: NO
923Z3: NO
1234: NO
123: NO
12: NO
1: NO
