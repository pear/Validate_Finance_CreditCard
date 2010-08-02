--TEST--
validate_ES_cif.phpt: Unit tests cif method for 'Validate/ES.php'
--FILE--
<?php
include (dirname(__FILE__).'/validate_functions.inc');
if (is_file(dirname(__FILE__) . '/../Validate/ES.php')) {
    require_once dirname(__FILE__) . '/../Validate/ES.php';
    $dataDir = dirname(__FILE__) . '/../data';
} else {
    require_once 'Validate/ES.php';
    $dataDir = null;
}

echo "Test Validate_ES\n";
echo "****************\n";

$cifs = array ( 'A58818501' => 'OK'
              , 'B00000000' => 'OK'
              , 'C0000000J' => 'OK'
              , 'D00000000' => 'OK'
              , 'E00000000' => 'OK'
              , 'F00000000' => 'OK'
              , 'G00000000' => 'OK'
              , 'H00000000' => 'OK'
              , 'I00000000' => 'KO'
              , 'I0000000J' => 'KO'
              , 'J00000000' => 'OK'
              , 'K0000000J' => 'OK'
              , 'L0000000J' => 'OK'
              , 'M0000000J' => 'OK'
              , 'N0000000J' => 'OK'
              , 'O00000000' => 'KO'
              , 'O0000000J' => 'KO'
              , 'P0000000J' => 'OK'
              , 'Q0000000J' => 'OK'
              , 'R0000000J' => 'OK'
              , 'S0000000J' => 'OK'
              , 'T00000000' => 'KO'
              , 'T0000000J' => 'KO'
              , 'U00000000' => 'OK'
              , 'V00000000' => 'OK'
              , 'W0000000J' => 'OK'
              , 'X00000000' => 'KO'
              , 'X0000000J' => 'KO'
              , 'Y00000000' => 'KO'
              , 'Y0000000J' => 'KO'
              , 'Z00000000' => 'KO'
              , 'Z0000000J' => 'KO'
              , 'B0000000J' => 'KO'
              , 'BC0000000' => 'KO'
              , '123456678' => 'KO'
              , 'B-00000000' => 'OK'
              , 'K-0000000-J' => 'OK'
              );

$errorFound = false;
$errorFound = $errorFound || test_func(array('validate_ES','cif'), $cifs );
echo ($errorFound) ? '... FAILED' : '... SUCCESS';
?>
--EXPECT--
Test Validate_ES
****************
---------
Test validate_ES::cif
 _ Value                  State Return
 V = validation result is right
 X = validation result is wrong
 V A58818501            : OK    OK
 V B00000000            : OK    OK
 V C0000000J            : OK    OK
 V D00000000            : OK    OK
 V E00000000            : OK    OK
 V F00000000            : OK    OK
 V G00000000            : OK    OK
 V H00000000            : OK    OK
 V I00000000            : KO    KO
 V I0000000J            : KO    KO
 V J00000000            : OK    OK
 V K0000000J            : OK    OK
 V L0000000J            : OK    OK
 V M0000000J            : OK    OK
 V N0000000J            : OK    OK
 V O00000000            : KO    KO
 V O0000000J            : KO    KO
 V P0000000J            : OK    OK
 V Q0000000J            : OK    OK
 V R0000000J            : OK    OK
 V S0000000J            : OK    OK
 V T00000000            : KO    KO
 V T0000000J            : KO    KO
 V U00000000            : OK    OK
 V V00000000            : OK    OK
 V W0000000J            : OK    OK
 V X00000000            : KO    KO
 V X0000000J            : KO    KO
 V Y00000000            : KO    KO
 V Y0000000J            : KO    KO
 V Z00000000            : KO    KO
 V Z0000000J            : KO    KO
 V B0000000J            : KO    KO
 V BC0000000            : KO    KO
 V 123456678            : KO    KO
 V B-00000000           : OK    OK
 V K-0000000-J          : OK    OK
... SUCCESS
