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
test(Validate::date('121202','%d%m%y'), true);
// 2
test(Validate::date('21202','%d%m%y'), false);
// 3
test(Validate::date('121402','%d%m%y'), false);
// 4
test(Validate::date('12120001','%d%m%Y'), true);
// 5
test(Validate::date('2120001','%j%m%Y'), true);
// 6
test(Validate::date('220001','%j%n%Y'), true);
// 7
test(Validate::date('2299','%j%n%y'), true);
// 8
test(Validate::date('12121999','%d%m%Y', array('01','01','1995')), true);
// 9
test(Validate::date('12121996','%d%m%Y', array('01','01','1995'), array('01','01','1997')), true);
// 10
test(Validate::date('29022002','%d%m%Y'), false);
// 11
test(Validate::date('12.12.1902','%d.%m.%Y'), true);
// 12
test(Validate::date('12/12/1902','%d/%m/%Y'), true);
// 13
test(Validate::date('12/12/1902','%d/%m/%Y'), true);
// 14
test(Validate::date('12:12:1902','%d:%m:%Y'), true);
// 15
test(Validate::date('12','%g'), true);
// 16
test(Validate::date('12','%G'), true);
// 17
test(Validate::date('13:00','%g:%i'), false);
// 18
test(Validate::date('24:59','%G:%i'), true);
// 19
test(Validate::date('25:00','%G:%i'), false);
// 20
test(Validate::date('25:00','%G:%i:%s'), false);
// 21
test(Validate::date('121902','%m%Y'), true);
// 22
test(Validate::date('13120001','%d%m%Y'), true);
?>
