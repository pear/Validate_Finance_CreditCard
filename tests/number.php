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
test(Validate::number(8), true);
// 2
test(Validate::number('8'), true);
// 3
test(Validate::number('-8'), true);
// 4
test(Validate::number(-8), true);
// 5
test(Validate::number('-8,', ','), false);
// 6
test(Validate::number('-8.0', ','), false);
// 7
test(Validate::number('-8,0', ',', 2), true);
// 8
test(Validate::number(8.0004, '.', 3), false);
// 9
test(Validate::number(8.0004, '.', 4), true);
// 10
test(Validate::number('-8', null, null, 1, 9), false);
// 11
test(Validate::number('-8', null, null, -8, -7), true);
// 12
test(Validate::number('-8.02', '.', null, -8, -7), false);
// 13
test(Validate::number('-8.02', '.', null, -9, -7), true);
// 14
test(Validate::number('-8.02', '.,', null, -9, -8), true);
?>
