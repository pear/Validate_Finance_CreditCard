--TEST--
validate_ptBR.phpt: Unit tests for
--FILE--
<?php
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate/ptBR.php';

echo "Test Validate_ptBR\n";
echo "****************\n";

$postalCodes = array(

         /* acceptable formats */

        '69990000',  // OK
        '69950000',  // OK
        '69983000',  // OK
        '69928000',  // OK
        '38412158',  // OK
        '45225000',  // OK
        '46865000',  // OK
        '46755000',  // OK
        '44920000',  // OK
        
        '69990 000', // OK
        '69950 000', // OK
        '69983 000', // OK
        '69928 000', // OK
        '38412 158', // OK
        '45225 000', // OK
        '46865 000', // OK
        '46755 000', // OK
        '44920 000', // OK
        
	'38.412 158', // OK
	'38.412158',  // OK
	'38412 158', // OK
	'38412-158', // OK
	'38.412-158', // OK
	
        '69990-000', // OK
        '69950-000', // OK
        '69983-000', // OK
        '69928-000', // OK
        '38412-158', // OK
        '45225-400', // OK
        '46865-000', // OK
        '46755-000', // OK
        '44920-000', // OK

         /* not acceptable */

        '6999A0000',  // NOK
        '6995  0000',  // NOK
        '6998333000',  // NOK
        '69928sd000',  // NOK
        '238412158',  // NOK
        '4sd5225000',  // NOK
        '423526865000',  // NOK
        '4dxc6755000',  // NOK
        '449vb20000',  // NOK
        
        '69990s000', // NOK
        '69950  000', // NOK
        '69983--000', // NOK
        '699-28000', // NOK
        '38412325158', // NOK
        '45225ss000', // NOK
        '4686534000', // NOK
        '4675534000', // NOK
        '44920we000', // NOK
        
        '699903434000', // NOK
        '69950-dfg000', // NOK
        '69983c-000', // NOK
        '69928-vc000', // NOK
        '38412-v158', // NOK
        '45225v-400', // NOK
        '46860400x', // NOK
        '46755-0200', // NOK
        '449203-000', // NOK

);

$phonenumbers = array(
                /* test allowed eight digit numbers */
                array('3875-0987', false), // OK
                array('2875 0987', false), // OK
                array('28751987', false), // OK
                array('4175-0987', false), // OK
                array('5075-a987', false), // NOK
                array('1875 098a', false), // NOK
                array('8dy0 4985', false), // NOK
                array('9844398x',false), // NOK

                /* test ten digit numbers without a required area code */
                array('(467) 875-0987', false), // NOK
                array('(467)-875-0987', false), // NOK
                array('(467)875-0987', false), // NOK
                array('(467) 875 0987', false), // NOK
                array('(467)-875 0987', false), // NOK
                array('(467)875 0987', false), // NOK
                array('(467) 8750987', false), // NOK
                

                /* test ten digit numbers with a required area code */
                array('(34)2875-0987', true), // OK
                array('(46)28750987', true), // OK
                array('(43)5323 0987', true), // OK
                array('46 4875 0987', true), // OK
                array('(55)3875 0987', true), // OK
                array('(11)87540987', true), // OK
                array('21 8725 0987', true), // OK
                array('11 28750987', true), // OK
                array('1128750987', true), // OK
                array('11-2875-0987', true), // OK
                array('(11)-2875-0987', true), // OK
                array('(11)2875-0987', true), // OK
                array('(11) 2875-0987', true), // OK
                array('(11)2875 0987', true), // OK
                array('(11) 28750987', true), // OK
                array('(11) 2875 0987', true), // OK
                array('(11)-2875 0987', true), // OK

                array('11s28750987', true), // NOK
                array('11-2875- 0987', true), //NOK
                array('(11)-28735-230987', true), // NOK
                array('(11)2875-0sx987', true), // NOK
                array('(11332875-0987', true), // NOK
                array('(11)  32875 0987', true), // NOK
                array('(11) 2875  0987', true), // NOK
                array('(11) 2875- 0987', true), // NOK



                array('(13)4175-0987', false), // NOK
                array('(46)4075-0987', false), // NOK
                array('(46)-fawe-0987', false), // NOK
                array('(434)-8475-0987', false), // NOK
                array('(11)9df3-0987', false), // NOK
                array('11-3487-0987', false), // NOK
                array('(4a7) 875-0987', true), // NOK
                array('(467)-085-0987', true), // NOK
                array('(467)87-0987', true), // NOK
                array('(46e) t75 0987', true), // NOK
                array('(313 535-8553', true), // NOK
                array('(123) 456-78', true), // NOK
                array('(517) 474-', true), // NOK
            );

$regions = array(
                'MG', // OK
                'SP', // OK
                'ES', // OK
                'RJ', // OK
                'DH', // NOK
                'ILL', // NOK
                'SD', // NOK
                'SPS', // NOK
                'NL'); // NOK
$cpf = array(

  '32181248400', // OK
  '05508262628', // OK
  '05036880617', // OK
  '05074718651', // OK
  '05911327619', // OK
  '06476719645', // OK
  '03707212688', // OK
  '928492372z-', // NOK
  'x3523652336', // NOK
  '00000000000', // NOK
  '11111111111', // NOK
  '22222222222', // NOK
  '33333333333', // NOK
  '44444444444', // NOK
  '55555555555', // NOK
  '66666666666', // NOK
  '77777777777', // NOK
  '88888888888', // NOK
  '99999999999', // NOK
); 

$cnpj = array(
'17855473000173', // OK
'36851287000100', // OK
'25786291000116', // OK
'17570358000152', // OK
'0546sd42226683', // NOK
);






echo "Test postalCode\n";
foreach ($postalCodes as $postalCode) {
    echo "{$postalCode}: ".$noYes[Validate_ptBR::postalCode($postalCode)]."\n";
}

echo "Test phonenumber\n";
foreach ($phonenumbers as $phonenumber) {
    echo "{$phonenumber[0]} ".($phonenumber[1]? "(10)" : "(7)").": ".
        $noYes[Validate_ptBR::phonenumber($phonenumber[0], $phonenumber[1])]."\n";
}

echo "Test region\n";
foreach ($regions as $region) {
    echo "{$region}: ".$noYes[Validate_ptBR::region($region)]."\n";
}

echo "Test cpf\n";
foreach ($cpf as $cp) {
    echo "{$region}: ".$noYes[Validate_ptBR::cpf($cp)]."\n";
}

echo "Test cnpj\n";
foreach ($cnpj as $cn) {
    echo "{$region}: ".$noYes[Validate_ptBR::cnpj($cn)]."\n";
}





?>
--EXPECT--
Test Validate_ptBR
****************
Test postalCode
69990000: YES
69950000: YES
69983000: YES
69928000: YES  
38412158: YES  
45225000: YES  
46865000: YES  
46755000: YES  
44920000: YES       
69990 000: YES
69950 000: YES
69983 000: YES
69928 000: YES
38412 158: YES
45225 000: YES
46865 000: YES
46755 000: YES
44920 000: YES  
38.412 158: YES
38.412158: YES
38412 158: YES
38412-158: YES
38.412-158: YES
69990-000: YES
69950-000: YES 
69983-000: YES
69928-000: YES
38412-158: YES
45225-400: YES
46865-000: YES
46755-000: YES
44920-000: YES
6999A0000: NO
6995  0000: NO
6998333000: NO
69928sd000: NO
238412158: NO
4sd5225000: NO
423526865000: NO
4dxc6755000: NO
449vb20000: NO
69990s000: NO
69950  000: NO
69983--000: NO
699-28000: NO
38412325158: NO
45225ss000: NO
4686534000: NO
4675534000: NO
44920we000: NO
699903434000: NO
69950-dfg000: NO
69983c-000: NO
69928-vc000: NO
38412-v158: NO
45225v-400: NO
46860400x: NO
46755-0200: NO
449203-000: NO
Test phonenumber
3875-0987: YES
2875 0987: YES
28751987: YES
4175-0987: YES
5075-a987: NO
1875 098a: NO
8dy0 4985: NO
9844398x: NO
(467) 875-0987: NO
(467)-875-0987: NO
(467)875-0987: NO
(467) 875 0987: NO
(467)-875 0987: NO
(467)875 0987: NO
(467) 8750987: NO
(34)2875-0987: YES
(46)28750987: YES
(43)5323 0987: YES
46 4875 0987: YES
(55)3875 0987: YES
(11)87540987: YES
21 8725 0987: YES
11 28750987: YES
1128750987: YES
11-2875-0987: YES
(11)-2875-0987: YES
(11)2875-0987: YES
(11) 2875-0987: YES
(11)2875 0987: YES
(11) 28750987: YES
(11) 2875 0987: YES
(11)-2875 0987: YES
11s28750987: NO
11-2875- 0987: NO
(11)-28735-230987: NO
(11)2875-0sx987: NO
(11332875-0987: NO
(11)  32875 0987: NO
(11) 2875  0987: NO
(11) 2875- 0987: NO
(13)4175-0987: NO
(46)4075-0987: NO
(46)-fawe-0987: NO
(434)-8475-0987: NO
(11)9df3-0987: NO
11-3487-0987: NO
(4a7) 875-0987: NO
(467)-085-0987: NO
(467)87-0987: NO
(46e) t75 0987: NO
(313 535-8553: NO
(123) 456-78: NO
(517) 474-: NO
Test region
MG: YES
SP: YES
ES: YES
RJ: YES
DH: NO
ILL: NO
SD: NO
SPS: NO
NL: NO
Test cpf
32181248400: YES
05508262628: YES
05036880617: YES
05074718651: YES
05911327619: YES
06476719645: YES
03707212688: YES
928492372z-: NO
x3523652336: NO
00000000000: NO
11111111111: NO
22222222222: NO
33333333333: NO
44444444444: NO
55555555555: NO
66666666666: NO
77777777777: NO
88888888888: NO
99999999999: NO
Test cnpj
17855473000173: YES
36851287000100: YES
25786291000116: YES
17570358000152: YES
0546sd42226683: NO


