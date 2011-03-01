--TEST--
Validate_NZ.phpt: Unit tests for
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');

if (is_file(dirname(__FILE__) . '/../Validate/NZ.php')) {
    require_once dirname(__FILE__) . '/../Validate/NZ.php';
    $dataDir = dirname(__FILE__) . '/../data';
} else {
    require_once 'Validate/NZ.php';
    $dataDir = null;
}

echo " Test Validate_NZ\n";
echo "****************\n";

$postalCodes = array(
                     "0110",     //Ok
                     "1010",     //Ok

                         /* If $Strong is set to TRUE these will not validate */
                     
                     "0000",    //NOK
                     "1501",    //NOK
                     "1111",    //NOK
                     "0800",    //NOK
                       
                         /* Theres no way these would validate either way */
                     
                     "011",     //NOK
                     "a112",    //NOK
                     "101010",  //NOK
                     "O1lO",    //NOK
                                          
                     "0610",     //Ok
                     "0600",     //Ok
                     "2012",    //Ok
                     "2105",    //Ok
                     "0505",    //Ok
                     "1081",    //Ok
                     "1022",    //Ok
                     "2102",    //Ok
                     "2010",    //Ok
                     "2022",    //Ok
                     "2013",    //Ok
                     "0630",    //Ok
                     "0614",    //Ok
                     "0612",    //Ok
                     "2014",    //Ok
                     "1025",    //Ok
                     "0931",    //Ok    
		     "9893"	//Ok
                     );
                             
$phoneNumbers = array(        
                      
                      /*     Landlines     */                    

                      "(03) 684-5018",  //Ok        
                      "063471122",        //Ok    
                      "(06)3471122",    //Ok
                      "03-684-5018",    //Ok
                      
                      /* Must Have correct area code (3,4,6,7 and 9) */
                      
                      "056845018",        //NOk    
                      "08-684-5018",    //NOk                    
                      "(02) 631-7658",    //NOk
                      
                      /* Now Support for Country code */
                      
                      "+64 03 684-5018", //Ok
                      "64-03-684-5018", //Ok
                      "64036845018", //Ok
                      "64 03 684 5018", //Ok
                      
                      
                      /*    Mobile Phones    */
                      "0-21-343 183", // Ok
                      "0272913164",        //Ok
                      "021 234 5678",    //Ok
                      "027-123-4567",    //Ok
                      "(025) 234-5678",    //NOk  - shut down on 31 March 2007
                      
                      /*    Must have correct network Prefix (021,027,025)    */
                      
                      "0232345678",        //NOk
                      "024-321-8765",    //NOk
                      "1112223456",      //NOk
                      
                      /*    0800,0900 and 0508    */
                      
                      "0800 838383",    //Ok
                      "0800-30-40-50",    //Ok
                      "0508 333245",    //Ok
                      "0900 202 020",    //Ok
                      "0508304050",        //Ok
                      "0300-603232",    //NOk
                      "1800 321 321",    //NOk
                      
                      
                      
                      "0500 123123",    //NOk
                      "0808 505050",    //NOk
                      "0908123456",    //NOk

                      "0800 269 296", //Ok
                      "0800 803 804", //Ok
                      "0800 275 269", //Ok
                      "0800 11 33 55", //Ok
                      "0800 400 600", //Ok
                      "0800 269 4663", //Ok
                      "0800 306 3010" //Ok
                      );
                      
$regions = array(
                        "AUK",    //Ok
                        "WTC",    //Ok
                        "WGN",    //Ok
                        "FIS",    //NOk
                        "SC",    //NOk
                        "CAB",    //NOk
                        "CAN"); //OK
                        
$bankAccounts = array(
                      "06-0889-0262506-00", //OK
                      "06 0889 0262506 00",    //OK
                      "060889026250600",    //OK
                      "00889026250600",        //NOk
                      "06-088902625060");    //NOk
                      

$IrdNumbers = array(
                    "087 784 215", //VALID
                    "036-410-232", //VALID
                    "071-321-321",//INVALID
                    "97 654 456", //INVALID
                    "83-366-3215", //INVALID
                    "987 784 215",); //INVALID

$Carreg = array("AE12Y3", //Ok
                "000000", //NOk
                "NY3Z14", //Ok
                "ABCDEF", //NOk
                "AI14W"); //OK

echo "----Test postalCode (lax)----\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_NZ::postalCode($postalCode, false, $dataDir)]."\n";
}

echo "----Test postalCode (strict)----\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_NZ::postalCode($postalCode, true, $dataDir)]."\n";
}
                        
echo "----Test phonenumber----\n";
foreach ($phoneNumbers as $phonenumber) {
    echo "{$phonenumber}: ".$noYes[Validate_NZ::phoneNumber($phonenumber,true)]."\n";
}

echo "----Test region----\n";
foreach ($regions as $region) {
    echo "{$region}: ".$noYes[Validate_NZ::region($region)]."\n";
}
                        
echo "----Test BankAccount----\n";
foreach ($bankAccounts as $bankAccount) {
    echo "{$bankAccount}: ".$noYes[Validate_NZ::bankCode($bankAccount)]."\n";
}



echo "----Test IRD Numbers (SSN)----\n";
foreach ($IrdNumbers as $ird) {
    echo "{$ird}: ".$noYes[Validate_NZ::ssn($ird)]."\n";
}

echo "----Test Vehicle License plates ----\n";
foreach ($Carreg as $Car) {
    echo "{$Car}: ".$noYes[Validate_NZ::carReg($Car)]."\n";
}
?>

--EXPECT--

Test Validate_NZ
****************
----Test postalCode (lax)----
0110: YES
1010: YES
0000: YES
1501: YES
1111: YES
0800: YES
011: NO
a112: NO
101010: NO
O1lO: NO
0610: YES
0600: YES
2012: YES
2105: YES
0505: YES
1081: YES
1022: YES
2102: YES
2010: YES
2022: YES
2013: YES
0630: YES
0614: YES
0612: YES
2014: YES
1025: YES
0931: YES
9893: YES
----Test postalCode (strict)----
0110: YES
1010: YES
0000: NO
1501: NO
1111: NO
0800: YES
011: NO
a112: NO
101010: NO
O1lO: NO
0610: YES
0600: YES
2012: YES
2105: YES
0505: YES
1081: YES
1022: YES
2102: YES
2010: YES
2022: YES
2013: YES
0630: YES
0614: YES
0612: YES
2014: YES
1025: YES
0931: YES
9893: YES
----Test phonenumber----
(03) 684-5018: YES
063471122: YES
(06)3471122: YES
03-684-5018: YES
056845018: NO
08-684-5018: NO
(02) 631-7658: NO
+64 03 684-5018: YES
64-03-684-5018: YES
64036845018: YES
64 03 684 5018: YES
0-21-343 183: YES
0272913164: YES
021 234 5678: YES
027-123-4567: YES
(025) 234-5678: NO
0232345678: NO
024-321-8765: NO
1112223456: NO
0800 838383: YES
0800-30-40-50: YES
0508 333245: YES
0900 202 020: YES
0508304050: YES
0300-603232: NO
1800 321 321: NO
0500 123123: NO
0808 505050: NO
0908123456: NO
0800 269 296: YES
0800 803 804: YES
0800 275 269: YES
0800 11 33 55: YES
0800 400 600: YES
0800 269 4663: YES
0800 306 3010: YES
----Test region----
AUK: YES
WTC: YES
WGN: YES
FIS: NO
SC: NO
CAB: NO
CAN: YES
----Test BankAccount----
06-0889-0262506-00: YES
06 0889 0262506 00: YES
060889026250600: YES
00889026250600: NO
06-088902625060: NO
----Test IRD Numbers (SSN)----
087 784 215: YES
036-410-232: YES
071-321-321: NO
97 654 456: NO
83-366-3215: NO
987 784 215: NO
----Test Vehicle License plates ----
AE12Y3: YES
000000: NO
NY3Z14: YES
ABCDEF: NO
AI14W: YES
