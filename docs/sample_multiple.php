<?php
//include_once "Validate/FR.php";
require_once "Validate.php";

if(function_exists('multiple')){
    die('FUCKKK');
}
/*
test(Validate::creditCard('6762195515061813'), true);
// 4
test(Validate::creditCard('6762195515061814'), false);
// 5
*/
/*
function rib($aCodeBanque, $aCodeGuichet='', $aNoCompte='', $aKey='')
function number($number, $decimal = null, $dec_prec = null, $min = null, $max = null)
*/
$values = array(
    'amount'=> '13234,344343',
    'name'  => 'foo@example.com',
    'rib'   => array(
                'codebanque'   => '33287',
                'codeguichet'  => '00081',
                'nocompte'     => '00923458141C',
                'key'          => '52'
                ),
    'rib2'  => array(
                'codebanque'   => '12345',
                'codeguichet'  => '12345',
                'nocompte'     => '12345678901',
                'key'          => '46'
                ),
    'cc'    => '6762195515061813',
    'cc2'   => '6762195515061814',
    'mail'  => 'foo@example.com',
    'hissiret' => '441 751 245 00016',
    'mystring' => 'ABCDEabcde'
    );
$opts = array(
    'amount'=> array('type'=>'number','decimal'=>',.','dec_prec'=>null,'min'=>1,'max'=>32000),
    'name'  => array('type'=>'email','check_domain'=>false),
    'rib'   => array('type'=>'fr_rib'),
    'rib2'  => array('type'=>'fr_rib'),
    'cc'    => array('type'=>'creditcard'),
    'cc2'   => array('type'=>'creditcard'),
    'mail'  => array('type'=>'email'),
    'hissiret' => array('type'=>'fr_siret'),
    'mystring' => array('type'=>'string',array('format'=>VAL_ALPHA, 'min_lenght'=>3))
    );

$result = Validate::multiple($values, $opts);

print_r($result);

?>