<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Pierre-Alain Joye <pajoye@php.net>                          |
// |          bertrand Gugger <bertrand@toggg.com>                        |
// +----------------------------------------------------------------------+
//
// $Id$
//
// Specific validation methods for data used in France

require_once 'Validate.php';

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
     *      http://xml.insee.fr/schema/nir.html
     * @param  string $number number or an array containaing the 'number'=>1234
     * @return bool           true if number is valid, otherwise false
     * @author Pierre-Alain Joye <pajoye@php.net>
     */
    function ssn($ssn)
    {
        $str    = strtolower(preg_replace('/[^0-9a-zA-Z]/', '', $ssn));

        $regexp = "/^([12])(\d\d)(\d\d)(\d\d|2a|2b)(\d\d\d)(\d\d\d)(\d\d)$/";

        if (!preg_match($regexp, $str, $parts)) {
            return false;
        }

        // special case for Corsica and DOM not 100% sure, but cannot test from a db :)
        if ($parts[4] == '2a' || $parts[4] == '2b' || $parts[4] == '9a' ||
        	$parts[4] == '9b' || $parts[4] == '9c' || $parts[4] == '9d') {
            if (strlen($str) == 15) {
                return true;
            } else {
                return false;
            }
        }

        if ($parts[2] < 1 || $parts[2] > 99) {
            return false;
        }
        if ($parts[3] < 1 || $parts[3] > 12) {
            return false;
        }
        if ($parts[4] < 1 || $parts[4] > 99) {
            return false;
        }

        // Person born outside France (region code==99), have to check insee country code
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
        $key = VALIDATE_FR_SSN_MODULUS - Validate::_modf($num, VALIDATE_FR_SSN_MODULUS);

        return ($key == $parts[7]) ? true : false;
    }

    /**
     * Validate a french RIB
     * see http://www.ecbs.org/Download/Tr201v3.9.pdf
     *
     * @param  string $aCodeBanque number or an array containaing the 'number'=>1234
     * @param  string $aCodeGuichet number or an array containaing the 'number'=>1234
     * @param  string $aNoCompte number or an array containaing the 'number'=>1234
     * @param  string $number number or an array containaing the 'number'=>1234
     * @return bool   true if number is valid, otherwise false
     * @author Pierre-Alain Joye <pajoye@php.net>
     * @author bertrand Gugger <bertrand@toggg.com>
     */
    function rib($rib)
    {
        if (is_array($rib)) {
            $codebanque = $codeguichet = $nocompte = $key = '';
            extract($rib);
        } else {
            return false;
        }
        $chars       = array('/[AJ]/','/[BKS]/','/[CLT]/','/[DMU]/','/[ENV]/','/[FOW]/','/[GPX]/','/[HQY]/','/[IRZ]/');
        $values      = array('1','2','3','4','5','6','7','8','9');

        $codebank    = preg_replace('/[^0-9]/', '', $codebanque);
        $officecode  = preg_replace('/[^0-9]/', '', $codeguichet);
        $account     = preg_replace($chars, $values, $nocompte);

        if (strlen($codebank) != 5) {
            return false;
        }

        if (strlen($officecode) != 5) {
            return false;
        }

        if (strlen($account) > 11) {
            return false;
        }

        if (strlen($key) != 2) {
            return false;
        }

        $l     = $codebank.$officecode.str_pad($account, 11, '0', STR_PAD_LEFT).$key.'0';
        $keyChk = 0;
        for ($i = 0; $i < 24; $i += 4) {
            $keyChk = ($keyChk*9 + substr($l, $i, 4)) % 97;
        }
        return !$keyChk;
    }


    /**
     * Validate a french SIREN number
     * see http://xml.insee.fr/schema/siret.html
     *
     * @param  string $siren  number or an array containaing the 'number'=>1234
     * @return bool           true if number is valid, otherwise false
     * @author Damien Seguy <dams@nexen.net>
     */
    function siren($siren)
    {
        $siren = str_replace(' ', '', $siren);
        if (!preg_match("/^(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)$/", $siren,	$match)) {
            return false;
        }
        $match[2] *= 2;
        $match[4] *= 2;
        $match[6] *= 2;
        $match[8] *= 2;
        $sum = 0;

        for ($i = 1; $i < count($match); $i++) {
            if ($match[$i] > 9) {
                $a = (int)substr($match[$i], 0, 1);
                $b = (int)substr($match[$i], 1, 1);
                $match[$i] = $a + $b;
            }
            $sum += $match[$i];
        }
        return (($sum % 10) == 0);
    }

    /**
     * Validate a french SIRET number
     * see http://xml.insee.fr/schema/siret.html
     *
     * @param  string $siret  number or an array containaing the 'number'=>1234
     * @return bool           true if number is valid, otherwise false
     * @author Damien Seguy <dams@nexen.net>
     */
    function siret($siret)
    {
        $siret = str_replace(' ', '', $siret);
        if (!preg_match("/^(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)$/", $siret, $match)) {
            return false;
        } else {
            if (!Validate_FR::siren(implode('', array_slice($match, 1, 9)))) {
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
        $sum = 0;

        for ($i = 1; $i < count($match); $i++) {
            if ($match[$i] > 9) {
                $a = (int)substr($match[$i], 0, 1);
                $b = (int)substr($match[$i], 1, 1);
                $match[$i] = $a + $b;
            }
            $sum += $match[$i];
        }
        return (($sum % 10) == 0);
    }

    /**
     * Validates a French Postal Code format
     *
     * @param string $postalCode the code to validate
     * @param   bool    optional; strong checks (e.g. against a list of postcodes) (not implanted)
     * @return boolean TRUE if code is valid, FALSE otherwise
     * @access public
     * @static
     * @todo Validate against department
     */
    function postalCode($postalCode, $strong = false)
    {
          return (bool)preg_match('/^(0[1-9]|[1-9][0-9])[0-9][0-9][0-9]$/', $postalCode);
    }

    /**
     * Validates a French "departement"
     *
     * @param string $region 2-digit department number
     * @return bool Whether the department is valid
     * @static
     */
    function region($region)
    {
        if (is_numeric($region)
            && floor($region) == ceil($region)) {
            switch (strlen($region)) {
                case 2:
                    if ($region >= 1 && $region <= 95
                        && $region != 20) {
                        return true;
                    }
                    break;
                case 3:
                    if ($region >= 971 && $region <= 975) {
                        return true;
                    }
                    break;
            }
        }
        switch (strtoupper($region)) {
            case '2A':
            case '2B':
                return true;
        }
        return false;
    }
}
?>
