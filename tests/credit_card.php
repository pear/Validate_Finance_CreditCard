<?php
require_once 'Validate.php';

function test($res, $expected) {
    static $no = 1;
    if ($res !== $expected) {
        echo "test $no failed\n";
    }
    $no++;
}
// 1
test(Validate::creditCard(0), false);
// 2
test(Validate::creditCard(-1), false);
// 3
test(Validate::creditCard('6762195515061813'), true);
// 4
test(Validate::creditCard('6762195515061814'), false);
// 5
test(Validate::creditCard(array('number' => '4405771709596026')), true);
// 6
test(Validate::creditCard('Big brother is watching you'), false);
?>
