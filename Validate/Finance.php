<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Financial functions for validation and calculation
 *
 * PHP Versions 4 and 5
 *
 * This class provides methods to validate:
 *  - IBAN (international bankaccount number)
 *  - Euro banknote id
 *
 * This source file is subject to version 3.01 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * @category  Validate
 * @package   Validate_Finance
 * @author    Tomas V.V.Cox  <cox@idecnet.com>
 * @author    Pierre-Alain Joye <pajoye@php.net>
 * @author    Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @copyright 1997-2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/Validate_Finance
 */

/**
* Requires the IBAN Finance Validate class
*/
require_once 'Validate/Finance/IBAN.php';

/**
 * This class provides methods to
 * - validate Euro banknote ids
 * - access to IBAN-quickcheck (use Validate_Finance_IBAN for more functionality)
 *
 * @category  File_Formats
 * @package   Validate_Finance
 * @author    Tomas V.V.Cox  <cox@idecnet.com>
 * @author    Pierre-Alain Joye <pajoye@php.net>
 * @author    Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @copyright 1997-2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://pear.php.net/package/Validate_Finance
 */
class Validate_Finance
{
    /**
     * Validation of an IBAN (international bankaccount number)
     *
     * @param string $iban IBAN to be validated
     *
     * @access public
     * @since  0.1
     * @return boolean   true if IBAN is okay
     */
    function iban($iban = '')
    {
        return Validate_Finance_IBAN::validate($iban);
    } // end func iban

    /**
     * Validation of a Euro banknote id
     *
     * @param string $banknote Euro banknote id to be validated
     *
     * @access public
     * @since  0.1
     * @return boolean true if Euro banknote id is okay
     */
    function banknoteEuro($banknote = '')
    {
        $euro_countrycode = array('J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 
                                  'U', 'V', 'W', 'X', 'Y', 'Z');

        if (strlen($banknote) != 12) {
            return false;
        }
        if (!in_array($banknote[0], $euro_countrycode)) {
            return false;
        }

        // build checksum, preparation
        $banknote_replace_chars = range('A', 'Z');
        foreach (range(10, 35) as $tempvalue) {
            $banknote_replace_values[] = strval($tempvalue);
        }

        // build checksum, substitute and calc
        $tempbanknote = str_replace(
            $banknote_replace_chars, 
            $banknote_replace_values, 
            substr($banknote, 0, -1)
        );
        $tempcheckvalue = 0;
        for ($strcounter = 0; $strcounter < strlen($tempbanknote); $strcounter++) {
            $tempcheckvalue += intval($tempbanknote[$strcounter]);
        }
        $tempcheckvalue %= 9; // modulo 9
        $tempcheckvalue  = 8 - $tempcheckvalue;

        return (intval($banknote[strlen($banknote)-1]) == $tempcheckvalue);
    } // end func banknoteEuro

} // end class Validate_Finance
