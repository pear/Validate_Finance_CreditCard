--TEST--
validate_PL.phpt: Unit tests for Validation of Polish data.
--FILE--
<?php
$noYes = array('KO', 'OK');
$symbol = array('!X!', ' V ');

include (dirname(__FILE__).'/validate_functions.inc');

if (is_file(dirname(__FILE__) . '/../Validate/PL.php')) {
    require_once dirname(__FILE__) . '/../Validate/PL.php';
} else {
    require 'Validate/PL.php';
}

$validate = new Validate_PL;
echo "Test Validate_PL\n";
echo "****************\n";

$nips = array ("7680002466","3385035171",);
$regons = array("590096454", "590096455","002188077");
$pesels = array("40121008916","60052219867","33090901995","49110603787",
                "14665303253","42176454020","30261330924","18659635322");
$banks = array("94332544", "94332557", "94332560", "94332573", "94332586",
              "94332537", "94332538", "94332539", "94332540", "94332541");

echo "\nTest NIP\n";
foreach($nips as $nip) {
    printf("%s: %s\n", $nip, $noYes[$validate->nip($nip)]);
}
echo "\nTest REGON\n";
foreach($regons as $regon) {
    printf("%s: %s\n", $regon, $noYes[$validate->regon($regon)]);
}
echo "\nTest PESEL\n";
foreach($pesels as $pesel) {
    printf("%s: %s\n", $pesel, $noYes[$validate->pesel($pesel,$dn)]);
}
echo "\nTest Bank Branch\n";
foreach($banks as $bank) {
    printf("%s: %s\n", $bank, $noYes[$validate->bankBranch($bank,$dn)]);
}
exit(0);
?>

--EXPECT--
Test Validate_PL
****************

Test NIP
7680002466: OK
3385035171: KO

Test REGON
590096454: OK
590096455: KO
002188077: OK

Test PESEL
40121008916: OK
60052219867: OK
33090901995: OK
49110603787: OK
14665303253: OK
42176454020: KO
30261330924: KO
18659635322: KO

Test Bank Branch
94332544: OK
94332557: OK
94332560: OK
94332573: OK
94332586: OK
94332537: KO
94332538: KO
94332539: KO
94332540: KO
94332541: KO
