<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Specific validation methods for data used in Spain
 *
 * PHP Versions 4 and 5
 *
 * @category  Validate
 * @package   Validate_ES
 * @author    Tomas V.V.Cox <cox@idecnet.com>
 * @author    Byron Adams <byron.adams54@gmail.com>
 * @author    Jesús Espino <jespinog@gmail.com>
 * @copyright 1997-2005 Pierre-Alain Joye,Tomas V.V.Cox
 * @copyright 2006 Byron Adams
 * @copyright 2010 Jesús Espino
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_ES
 */

// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 Pierre-Alain Joye,Tomas V.V.Cox              |
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
// |         Byron Adams <byron.adams54@gmail.com>                        |
// |         Jesús Espino <jespinog@gmail.com>                            |
// +----------------------------------------------------------------------+
//

/**
* Requires base class Validate
*/
require_once 'Validate.php';

/**
 * Data validation class for Spain
 *
 * This class provides methods to validate:
 *  - Spanish DNI number ("El documento de la identificación nacional")
 *
 * @category  Validate
 * @package   Validate_ES
 * @author    Tomas V.V.Cox <cox@idecnet.com>
 * @author    Byron Adams <byron.adams54@gmail.com>
 * @author    Jesús Espino <jespinog@gmail.com>
 * @copyright 1997-2005 Pierre-Alain Joye, Tomas V.V.Cox
 * @copyright 2006 Byron Adams
 * @copyright 2010 Jesús Espino
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_ES
 */
class Validate_ES
{
    /**
    * Validate Spanish DNI number ("El documento de la identificación nacional")
    *
    * In Spain, all Spanish citizens are issued with a DNI
    * the numbers are used as identification for almost all purposes.
    *
    * @param string $dni El Documento Nacional de Indentidad a chequear
    *
    * @return bool returns true on success false otherwise
    * @author    Tomas V.V.Cox <cox@idecnet.com>
    * @author    Byron Adams <byron.adams54@gmail.com>
    * @author    Jesús Espino <jespinog@gmail.com>
    * @link      http://es.wikipedia.org/wiki/Algoritmo_para_obtener_la_letra_del_NIF
    * @link      http://nationalidentificationnumber.quickseek.com/#Spain
    */
    function dni($dni)
    {
        $dni = str_replace("-", "", trim($dni));
        $letters = 'TRWAGMYFPDXBNJZSQVHLCKET';

        $start = (int)(strtoupper($dni{0}) == "X");

        $number = substr($dni, $start, -1);
        $letter = strtoupper(substr($dni, -1));

        if (!ctype_digit($number) || !ctype_alpha($letter)) {
            return false;
        }

        return ($letter == $letters{$number % 23});
    }

    /**
     * Validate Spanish CIF number
     *
     * In Spain, all Spanish companies are issued with a CIF code.
     *
     * @param string $cif CIF to check
     *
     * @return bool returns true on success false otherwise
     */
    function cif($cif)
    {
        $cif = strtoupper(str_replace("-", "", trim($cif)));

        $letters = 'ABCDEFGHJKLMNPRQSUVW';
        $letters2 = 'ABCDEFGHIJ';

        if (preg_match("/^[$letters]\d{7}[\d[$letters2]$/", $cif) == 0) {
            return false;
        }

        $letter = substr($cif, 0, 1);

        $provinceCode = substr($cif, 1, 2);

        $number = substr($cif, 3, 5);
        $controlCode = substr($cif, 8, 1);

        if (strpos("CKLMNPQRSW", $letter)!==false && ctype_digit($controlCode)) {
            return false;
        }

        if (strpos("ABDEFGHJUV", $letter)!==false && ctype_alpha($controlCode)) {
            return false;
        }

        $a = $provinceCode[1]+$number[1]+$number[3];

        $b = 0;
        $oddNumbers = array($provinceCode[0], $number[0], $number[2], $number[4]);
        foreach ($oddNumbers as $number) {
            $x = $number*2;
            if ($x >= 10) {
                $x = ($x % 10)+1;
            }
            $b += $x;
        }

        $c = $a + $b;

        $e = $c % 10;

        if ($e != 0) {
            $d = 10-$e;
        } else {
            $d = 0;
        }

        if ((int)$controlCode != $d && $letters2[$d] != $controlCode) {
            return false;
        }

        return true;
    }

    /**
     * Validate Spanish Account Client Code number
     *
     * In Spain, all Spanish banking accounts are issued with a CCC code.
     *
     * @param string $ccc CCC to check
     *
     * @return bool returns true on success false otherwise
     */
    function ccc($ccc)
    {
        $ccc = str_replace(array("-", " "), "", trim($ccc));

        if (preg_match("/\d{20}$/", $ccc) == 0) {
            return false;
        }

        $weight = array(1, 2, 4, 8, 5, 10, 9, 7, 3, 6);

        $entity = substr($ccc, 0, 4);
        $office = substr($ccc, 4, 4);

        $controlCode = substr($ccc, 8, 2);
        $account     = substr($ccc, 10, 10);

        $firstCode  = "00".$entity.$office;
        $secondCode = $account;

        $firstCodeResult = 0;
        for ($x=0; $x < 10; $x++) {
            $firstCodeResult += (int)$firstCode[$x] * $weight[$x];
        }

        $firstCodeMod    = $firstCodeResult % 11;
        $firstCodeResult = 11 - $firstCodeMod;

        if ($firstCodeResult == 10) {
            $firstCodeResult = 1;
        }

        if ($firstCodeResult == 11) {
            $firstCodeResult = 0;
        }

        $secondCodeResult = 0;
        for ($x=0; $x < 10; $x++) {
            $secondCodeResult += (int)$secondCode[$x] * $weight[$x];
        }

        $secondCodeMod    = $secondCodeResult % 11;
        $secondCodeResult = 11 - $secondCodeMod;

        if ($secondCodeResult == 10) {
            $secondCodeResult = 1;
        }

        if ($secondCodeResult == 11) {
            $secondCodeResult = 0;
        }

        if ($firstCodeResult == $controlCode[0]
            && $secondCodeResult == $controlCode[1]
        ) {
            return true;
        }

        return false;
    }


    /**
     * Validate Spanish Social Security number
     *
     * In Spain, all Spanish have a social security number.
     *
     * @param string $ssn Social security number to check
     *
     * @return bool returns true on success false otherwise
     */
    function ssn($ssn)
    {
        $ssn = str_replace(array("-", " ", "/"), "", trim($ssn));

        $a = substr($ssn, 0, 2);
        $b = substr($ssn, 2, 8);

        $code = substr($ssn, 10, 2);

        $snn = sprintf("%012d", (int)$ssn);

        if (preg_match("/^\d{12}$/", $ssn) == 0) {
            return false;
        }

        if ((int)$b < 10000000) {
            $d = (int)$b + ((int)$a * 10000000);
        } else {
            $d = $a . preg_replace("/0*$/", "", $b);
        }

        $c = (int)$d % 97;

        return ($c == $code);
    }


    /**
     * Validate Spanish Postal Code number
     *
     * @param string $postcode postcode to validate
     *
     * @return  bool    true if postcode is ok, false otherwise
     */
    function postalCode($postcode)
    {
        $postcode = trim($postcode);
        if (preg_match("/^\d{5}$/", $postcode) == 0) {
            return false;
        }

        $provinceCode = substr($postcode, 0, 2);
        if (((int) $provinceCode > 52) || ((int) $provinceCode < 1)) {
            return false;
        }

        return true;
    }

}
