<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Pierre-Alain Joye <paj@pearfr.org>                          |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id$
//
// Specific validation methods for data used in France
//
require_once "Validate.php";

define('VALIDATE_FR_SSN_MODULUS', 97);

class Validate_FR
{
    /**
     * Validate a french SSN
     *
     * TO DO :
     * strong validation against the INSEE cities databases (rpc or local DB)
     *
     * This function checks given number according the specs
     * available here:
     *      http://www.dads.cnav.fr/tds/Stru0103.htm
     *
     * @param  string $number number or an array containaing the 'number'=>1234
     * @return bool           true if number is valid, otherwise false
     * @author Pierre-Alain Joye <paj@pearfr.org>
     */
    function ssn($number)
    {
        if( is_array($number) ) {
            extract( $number );
        }

        $str    = strtolower(preg_replace('/[^0-9a-zA-Z]/','',$number));

        $regexp = "/^([12])(\d\d)(\d\d)(\d\d|2a|2b)(\d\d\d)(\d\d\d)(\d\d)$/";

        if ( ! preg_match($regexp,$str,$parts) ) {
            return false;
        }

        // special case for Corsica and DOM not 100% sure, but cannot test from a db :)
        if( $parts[4]=='2a' || $parts[4]=='2b' || $parts[4]=='9a'
            || $parts[4]=='9b' || $parts[4]=='9c' || $parts[4]=='9d'
            ){
            if (strlen($str)==15){
                return true;
            } else {
                return false;
            }
        }

        if($parts[2]<1 || $parts[2]>99){
            return false;
        }
        if($parts[3]<1 || $parts[3]>12){
            return false;
        }
        if($parts[4]<1 || $parts[4]>99){
            return false;
        }

        // Person borned outside France (region code==99), have to check insee country code
        // To do
        /*
        if($parts[4]==99){
            include 'FR_insee_country_codes.php';
            if ( !isset( $insee_country[$parts[5]] ) ){
                return false;
            }
        }
        */
        $num = $parts[1].$parts[2].$parts[3].$parts[4].$parts[5].$parts[6];
        $key = VALIDATE_FR_SSN_MODULUS - Validate::_modf($num,VALIDATE_FR_SSN_MODULUS);

        return $key==$parts[7]?true:false;
    }

    /**
     * Validate a french RIB
     *
     * TO DO :
     * strong validation against the INSEE cities databases (rpc or local DB)
     *
     * This function checks given number according the specs
     * available here:
     *      http://www.dads.cnav.fr/tds/Stru0103.htm
     *
     * @param  string $aCodeBanque number or an array containaing the 'number'=>1234
     * @param  string $aCodeGuichet number or an array containaing the 'number'=>1234
     * @param  string $aNoCompte number or an array containaing the 'number'=>1234
     * @param  string $number number or an array containaing the 'number'=>1234
     * @return bool           true if number is valid, otherwise false
     * @author Pierre-Alain Joye <paj@pearfr.org>
     */
    function rib($aCodeBanque, $aCodeGuichet, $aNoCompte, $aKey)
    {
		if(is_array($aCodeBanque)) {
			extract($aCodeBanque);
		}
        $chars         = array('/[AJ]/','/[BKZ]/','/[CLT]/','/[DMU]/','/[ENV]/','/[FOW]/','/[GPX]/','/[HQY]/','/[IRZ]/');
        $values        = array('1','2','3','4','5','6','7','8','9');

        $codebank    = preg_replace('/[^0-9]/','',$aCodeBanque);
        $officecode  = preg_replace('/[^0-9]/','',$aCodeGuichet);
        $account     = preg_replace($chars,$values,$aNoCompte);

        echo " ". $codebank . " " . $officecode . " " . $account  . "\n";
        if (strlen($codebank)!=5){
            return false;
        }

        if (strlen($officecode)!=5){
            return false;
        }

        if (strlen($account)>11){
            return false;
        }

        $l      = $codebank.$officecode.$account;
        $a1    = (int)substr($l,0,7);
        $b1    = (int)substr($l,7,7);
        $c1    = (int)substr($l,15,7);

        $key   = 97 - Validate::_modf((62*$a1+34*$b1+3*$c1),97);

        if ($key==0){
            $key = 97;
        }
        return $key==$aKey;
    }


    /**
     * Validate a french SIREN number
     *
     *
     * @param  string $siren  number or an array containaing the 'number'=>1234
     * @return bool           true if number is valid, otherwise false
     * @author Damien Seguy, added by Pierre-Alain Joye <paj@pearfr.org>
     */
	function siren($siren)
	{
		if( is_array($siren) ) {
            extract( $number );
        }
		$siren = str_replace(' ', '', $siren);
		if (!preg_match("/^(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)$/",
			$siren,	$match)) {
			return false;
		} else {
		$match[2] *= 2;
		$match[4] *= 2;
		$match[6] *= 2;
		$match[8] *= 2;
		$somme = 0;

		for ($i = 1; $i<count($match); $i++) {
			if ($match[$i] > 9) {
				$a = (int)substr($match[$i], 0, 1);
				$b = (int)substr($match[$i], 1, 1);
				$match[$i] = $a + $b;
			}
			$somme += $match[$i];
		}
		return (($somme % 10) == 0);
	}

    /**
     * Validate a french SIRET number
     *
     *
     * @param  string $siret  number or an array containaing the 'number'=>1234
     * @return bool           true if number is valid, otherwise false
     * @author Damien Seguy, added by Pierre-Alain Joye <paj@pearfr.org>
     */
	function siret($siret)
	{
		$siret = str_replace(' ', '', $siret);
		if (
			!preg_match(
				"/^(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)$/",
				$siret,	$match
			)
		) {
			return false;
		} else {
			if( !Validate_FR::siren(implode('', array_slice($match, 1,9))) ) {
				return false;
			}
		}
		$match[1] *= 2;
		$match[3] *= 2;
		$match[5] *= 2;
		$match[7] *= 2;
		$match[9] *= 2;
		$match[11] *= 2;
		$match[13] *= 2;

		$somme = 0;

		for ($i = 1; $i<count($match); $i++) {
			if ($match[$i] > 9) {
				$a = (int)substr($match[$i], 0, 1);
				$b = (int)substr($match[$i], 1, 1);
				$match[$i] = $a + $b;
			}
			$somme += $match[$i];
		}

		return ( ($somme % 10) == 0 )

	}
}
?>