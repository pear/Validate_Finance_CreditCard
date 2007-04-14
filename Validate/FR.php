<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 Pierre-Alain Joye, Bertrand Gugger           |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Tomas V.V.Cox  <cox@idecnet.com>                             |
// |         Pierre-Alain Joye <pajoye@php.net>                           |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in France
 *
 * @category   Validate
 * @package    Validate_FR
 * @author     Pierre-Alain Joye <pajoye@php.net>
 * @author     Bertrand Gugger <bertrand@toggg.com>
 * @copyright  1997-2005 Pierre-Alain Joye, Bertrand Gugger
 * @license    http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_FR
 */

/**
* Requires base class Validate
*/
require_once 'Validate.php';

define('VALIDATE_FR_SSN_MODULUS', 97);

/**
 * Data validation class for France
 *
 * This class provides methods to validate:
 *  - Social insurance number (aka SSN)
 *  - French RIB
 *  - French SIREN number
 *  - French SIRET number
 *  - Postal code
 *  - French "departement"
 *
 * @category   PHP
 * @package    Validate
 * @subpackage Validate_FR
 * @author     Pierre-Alain Joye <pajoye@php.net>
 * @author     Bertrand Gugger <bertrand@toggg.com>
 * @copyright  1997-2005 Pierre-Alain Joye, Bertrand Gugger
 * @license    http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_FR
 */
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
     * @param string $region 2 (2A or 2B included) or 3 -digit department number
     * @return mixed The department's name, special chars numeric entities (true) or false
     * @static
     */
    function region($region)
    {
        static $return = array(
            '01' => 'Ain',
            '02' => 'Aisne',
            '03' => 'Allier',
            '04' => 'Alpes-de-Haute-Provence',
            '05' => 'Hautes-Alpes',
            '06' => 'Alpes-Maritimes',
            '07' => 'Ard&#232;che',
            '08' => 'Ardennes',
            '09' => 'Ari&#232;ge',
            '10' => 'Aube',
            '11' => 'Aude',
            '12' => 'Aveyron',
            '13' => 'Bouches-du-Rh&#244;ne',
            '14' => 'Calvados',
            '15' => 'Cantal',
            '16' => 'Charente',
            '17' => 'Charente-Maritime',
            '18' => 'Cher',
            '19' => 'Corr&#232;ze',
            '20' => 'Corse',
            '21' => 'C&#244;te-d\'Or',
            '22' => 'C&#244;tes d\'Armor',
            '23' => 'Creuse',
            '24' => 'Dordogne',
            '25' => 'Doubs',
            '26' => 'Dr&#244;me',
            '27' => 'Eure',
            '28' => 'Eure-et-Loir',
            '29' => 'Finist&#232;re',
            '2A' => 'Corse-du-Sud',
            '2B' => 'Haute-Corse',
            '30' => 'Gard',
            '31' => 'Haute-Garonne',
            '32' => 'Gers',
            '33' => 'Gironde',
            '34' => 'H&#233;rault',
            '35' => 'Ille-et-Vilaine',
            '36' => 'Indre',
            '37' => 'Indre-et-Loire',
            '38' => 'Is&#232;re',
            '39' => 'Jura',
            '40' => 'Landes',
            '41' => 'Loir-et-Cher',
            '42' => 'Loire',
            '43' => 'Haute-Loire',
            '44' => 'Loire-Atlantique',
            '45' => 'Loiret',
            '46' => 'Lot',
            '47' => 'Lot-et-Garonne',
            '48' => 'Loz&#232;re',
            '49' => 'Maine-et-Loire',
            '50' => 'Manche',
            '51' => 'Marne',
            '52' => 'Haute-Marne',
            '53' => 'Mayenne',
            '54' => 'Meurthe-et-Moselle',
            '55' => 'Meuse',
            '56' => 'Morbihan',
            '57' => 'Moselle',
            '58' => 'Ni&#232;vre',
            '59' => 'Nord',
            '60' => 'Oise',
            '61' => 'Orne',
            '62' => 'Pas-de-Calais',
            '63' => 'Puy-de-D&#244;me',
            '64' => 'Pyr&#233;n&#233;es-Atlantiques',
            '65' => 'Hautes-Pyr&#233;n&#233;es',
            '66' => 'Pyr&#233;n&#233;es-Orientales',
            '67' => 'Bas-Rhin',
            '68' => 'Haut-Rhin',
            '69' => 'Rh&#244;ne',
            '70' => 'Haute-Sa&#244;ne',
            '71' => 'Sa&#244;ne-et-Loire',
            '72' => 'Sarthe',
            '73' => 'Savoie',
            '74' => 'Haute-Savoie',
            '75' => 'Paris',
            '76' => 'Seine-Maritime',
            '77' => 'Seine-et-Marne',
            '78' => 'Yvelines',
            '79' => 'Deux-S&#232;vres',
            '80' => 'Somme',
            '81' => 'Tarn',
            '82' => 'Tarn-et-Garonne',
            '83' => 'Var',
            '84' => 'Vaucluse',
            '85' => 'Vend&#233;e',
            '86' => 'Vienne',
            '87' => 'Haute-Vienne',
            '88' => 'Vosges',
            '89' => 'Yonne',
            '90' => 'Territoire de Belfort',
            '91' => 'Essonne',
            '92' => 'Hauts-de-Seine',
            '93' => 'Seine-St-Denis',
            '94' => 'Val-de-Marne',
            '95' => 'Val-D\'Oise',
            '971' => 'Guadeloupe',
            '972' => 'Martinique',
            '973' => 'Guyane',
            '974' => 'R&#233;union',
            '975' => 'Saint-Pierre-et-Miquelon',
            '976' => 'Mayotte',
            '984' => 'Terres Australes et Antarctiques',
            '985' => 'Mayotte', // deprecated
            '986' => 'Wallis-et-Futuna',
            '987' => 'Polyn&#233;sie française',
            '988' => 'Nouvelle-Cal&#233;donie'
        );
    
        if (is_numeric($region)
            && floor($region) == ceil($region)) {
            switch (strlen($region)) {
                case 2:
                    if ($region >= 1 && $region <= 95
                        && $region != 20) {
                        return $return[$region] || true;
                    }
                    break;
                case 3:
                    /* DOM/TOM et collectivitees OM  */
                    if (($region >= 971 && $region <= 976) || ($region >= 984 && $region <= 988)) {
                        return $return[$region] || true;
                    }
                    break;
            }
        }
        switch (strtoupper($region)) {
            case '2A':
            case '2B':
                return $return[$region] || true;
        }
        return false;
    }
}
