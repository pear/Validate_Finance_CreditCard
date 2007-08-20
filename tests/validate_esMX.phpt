--TEST--
validate_esMX.phpt: Unit tests for validate_esMX

--FILE--
<?php
echo "Test Validate_esMX\n";
echo "****************\n";
/**
 * Sometimes the DATADIR variable is not replaced and therefor it keeps saying
 * DATADIR as it was a directory name instead of a variable
*/
if (is_file(dirname(__FILE__) . '/../Validate/esMX.php')) {
    require_once dirname(__FILE__) . '/../Validate/esMX.php';
    $postcodes_dir = dirname(__FILE__) . '/../data';
} else {
    require_once 'Validate/esMX.php';
    $postcodes_dir = null;
}

$noYes = array('NO', 'YES');
$postalCodes = array(
                     '061444', //NO
                     '0434 5', //NO
                     '423dd4', //NO
                     '68405',  //YES
                     '06140',  //YES
                     '',       //NO
                     '     ',  //NO
                     '63783',  //YES
                     '15490',  //YES / NO (if strong)
                     '75684',  //YES / NO (if strong)
                     );

$validate = new Validate_EsMX;
echo "Test postalCode\n";
foreach($postalCodes as $code) {
    echo $code . ':' . $noYes[$validate->postalCode($code, false, $postcodes_dir)]."\n";
}

echo "\nTest postalCode (strong)\n";
foreach($postalCodes as $code) {
    echo $code . ':' . $noYes[$validate->postalCode($code, true, $postcodes_dir)]."\n";
}

$dnis = array(
              'AAPR630321HDFLRC09', //YES
              'AAPR630321HDFLRC04', //NO
              'ACPR630321HDFLRC09', //NO
              'AAPR630321LDFLRC09', //NO
              'AAPR630321HIOLRC49', //NO
              'OIBR780920HDFRNN09', //YES
              'GIBR780920HDFRNN09', //
              'OMBR780920HDFRNN09', //NO
              'ACPR63032', //NO
              'xxxxxxxxxxxxxxxxxx', //NO
              '', //NO
              );

echo "\nTest dnis\n";
foreach($dnis as $dni) {
    echo $dni . ':' . $noYes[$validate->dni($dni)]."\n";
}

$phones = array(
                '56 16 15 60',    //YES
                '56220565',       //YES
                '213 6076',       //YES
                '414 33 33',      //YES
                '5063-3000',      //YES
                '506303000',      //NO
                '(50)3000',       //NO
                '(998)884 52 52', //NO
                '413-7452',       //YES
                );
echo "\nTest phones (no area code)\n";
foreach($phones as $phone) {
    echo $phone . ':' . $noYes[$validate->phone($phone, false)]."\n";
}

$phones = array(
                '01 (614) 413-7474',  //YES
                '01 59 7 54 7 14 43', //YES
                '(614) 414 52 97',    //NO
                '01 444 811 66 66',   //YES
                '01 77 73 29 40 66',  //YES
                '56220565',           //NO
                '213 6076',           //NO
                '414 33 33',          //NO
                );

echo "\nTest phones (with area code)\n";
foreach($phones as $phone) {
    echo $phone . ':' . $noYes[$validate->phone($phone, true)]."\n";
}
exit(0);
?>

--EXPECT--
Test Validate_esMX
****************
Test postalCode
061444:NO
0434 5:NO
423dd4:NO
68405:YES
06140:YES
:NO
     :NO
63783:YES
15490:YES
75684:YES

Test postalCode (strong)
061444:NO
0434 5:NO
423dd4:NO
68405:YES
06140:YES
:NO
     :NO
63783:YES
15490:NO
75684:NO

Test dnis
AAPR630321HDFLRC09:YES
AAPR630321HDFLRC04:NO
ACPR630321HDFLRC09:NO
AAPR630321LDFLRC09:NO
AAPR630321HIOLRC49:NO
OIBR780920HDFRNN09:YES
GIBR780920HDFRNN09:NO
OMBR780920HDFRNN09:NO
ACPR63032:NO
xxxxxxxxxxxxxxxxxx:NO
:NO

Test phones (no area code)
56 16 15 60:YES
56220565:YES
213 6076:YES
414 33 33:YES
5063-3000:YES
506303000:NO
(50)3000:NO
(998)884 52 52:NO
413-7452:YES

Test phones (with area code)
01 (614) 413-7474:YES
01 59 7 54 7 14 43:YES
(614) 414 52 97:NO
01 444 811 66 66:YES
01 77 73 29 40 66:YES
56220565:NO
213 6076:NO
414 33 33:NO
