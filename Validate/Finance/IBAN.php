<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4.0                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Stefan Neufeind <pear.neufeind@speedpartner.de>              |
// +----------------------------------------------------------------------+
//
// $Id$

$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODES'] =
        array(
            'AD' => 'Andorra',
            'AT' => 'Austria',
            'BE' => 'Belgium',
            'CH' => 'Swiss',
            'CZ' => 'Czech Republic',
            'DE' => 'Germany',
            'DK' => 'Denmark',
            'ES' => 'Spain',
            'FI' => 'Finland',
            'FR' => 'France',
            'GB' => 'Great Britain',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'HU' => 'Hungary',
            'IE' => 'Ireland',
            'IS' => 'Island',
            'IT' => 'Italy',
            'LU' => 'Luxembourg',
            'NL' => 'The Netherlands',
            'NO' => 'Norwegian',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'SE' => 'Sweden',
            'SI' => 'Slovenia'
        );

    /**
     * List of IBAN length; can be used for a quick check
     * @var  array
     */
$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_LENGTH'] =
        array(
            'AD' => 24,
            'AT' => 20,
            'BE' => 16,
            'CH' => 21,
            'CZ' => 24,
            'DE' => 22,
            'DK' => 18,
            'ES' => 24,
            'FI' => 18,
            'FR' => 27,
            'GB' => 22,
            'GI' => 23,
            'GR' => 27,
            'HU' => 28,
            'IE' => 22,
            'IS' => 26,
            'IT' => 27,
            'LU' => 20,
            'NL' => 18,
            'NO' => 15,
            'PL' => 28,
            'PT' => 25,
            'SE' => 24,
            'SI' => 19
        );
        
    /**
     * List of where the bankcode inside an IBAN starts (starting from 0) and its length
     * @var  array
     */
$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_BANKCODE'] =
        array(
            'AD' => array('start' =>  4, 'length' =>  8), // first 4 chars are bankcode, last 4 chars are the branch
            'AT' => array('start' =>  4, 'length' =>  5),
            'BE' => array('start' =>  4, 'length' =>  3),
            'CH' => array('start' =>  4, 'length' =>  5),
            'CZ' => array('start' =>  4, 'length' =>  4),
            'DE' => array('start' =>  4, 'length' =>  8),
            'DK' => array('start' =>  4, 'length' =>  4),
            'ES' => array('start' =>  4, 'length' =>  8),
            'FI' => array('start' =>  4, 'length' =>  6),
            'FR' => array('start' =>  4, 'length' => 10),
            'GB' => array('start' =>  4, 'length' =>  4),
            'GI' => array('start' =>  4, 'length' =>  4),
            'GR' => array('start' =>  4, 'length' =>  7), // first 3 chars bankcode, last 4 chars branch
            'HU' => array('start' =>  4, 'length' =>  7), // first 3 chars bankcode, last 4 chars branch, followed by 1 char (checksum)
            'IE' => array('start' =>  4, 'length' => 10), // first 4 chars bankcode, last 6 chars branch
            'IS' => array('start' =>  4, 'length' =>  4),
            'IT' => array('start' =>  4, 'length' => 11),
            'LU' => array('start' =>  4, 'length' =>  3),
            'NL' => array('start' =>  4, 'length' =>  4),
            'NO' => array('start' =>  4, 'length' =>  4),
            'PL' => array('start' =>  4, 'length' =>  8),
            'PT' => array('start' =>  4, 'length' =>  8),
            'SE' => array('start' =>  4, 'length' =>  3),
            'SI' => array('start' =>  4, 'length' =>  5)
        );

    /**
     * List of where the bankaccount-number inside an IBAN starts (starting from 0) and its length
     * @var  array
     */
$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_BANKACCOUNT'] =
        array(
            'AD' => array('start' => 12, 'length' => 12),
            'AT' => array('start' =>  9, 'length' => 11),
            'BE' => array('start' =>  7, 'length' =>  9),
            'CH' => array('start' =>  9, 'length' => 12),
            'CZ' => array('start' =>  8, 'length' => 16),
            'DE' => array('start' => 12, 'length' => 10),
            'DK' => array('start' =>  8, 'length' => 10),
            'ES' => array('start' => 12, 'length' => 12),
            'FI' => array('start' => 10, 'length' =>  8),
            'FR' => array('start' => 14, 'length' => 13),
            'GB' => array('start' =>  8, 'length' => 14),
            'GI' => array('start' =>  8, 'length' => 15),
            'GR' => array('start' => 11, 'length' => 16),
            'HU' => array('start' => 12, 'length' => 15), // followed by 1 char (checksum)
            'IE' => array('start' => 14, 'length' =>  8),
            'IS' => array('start' =>  8, 'length' => 18),
            'IT' => array('start' => 15, 'length' => 12),
            'LU' => array('start' =>  8, 'length' => 13),
            'NL' => array('start' =>  8, 'length' => 10),
            'NO' => array('start' =>  8, 'length' =>  7),
            'PL' => array('start' => 12, 'length' => 16),
            'PT' => array('start' => 12, 'length' => 13),
            'SE' => array('start' =>  7, 'length' => 17),
            'SE' => array('start' =>  7, 'length' =>  8) // followed by 1 char (checksum)
        );

$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_REGEX'] =
        array(
            'AD' => '/^AD[0-9]{2}[0-9]{8}[A-Z0-9]{12}$/',
            'AT' => '/^AT[0-9]{2}[0-9]{5}[0-9]{11}$/',
            'BE' => '/^BE[0-9]{2}[0-9]{3}[0-9]{9}$/',
            'CH' => '/^CH[0-9]{2}[0-9]{5}[A-Z0-9]{12}$/',
            'CZ' => '/^CH[0-9]{2}[0-9]{4}[0-9]{16}$/',
            'DE' => '/^DE[0-9]{2}[0-9]{8}[0-9]{10}$/',
            'DK' => '/^DK[0-9]{2}[0-9]{4}[0-9]{10}$/',
            'ES' => '/^ES[0-9]{2}[0-9]{8}[0-9]{12}$/',
            'FI' => '/^FI[0-9]{2}[0-9]{6}[0-9]{8}$/',
            'FR' => '/^FR[0-9]{2}[0-9]{10}[A-Z0-9]{13}$/',
            'GB' => '/^GB[0-9]{2}[A-Z]{4}[0-9]{14}$/',
            'GI' => '/^GB[0-9]{2}[A-Z]{4}[A-Z0-9]{15}$/',
            'GR' => '/^GB[0-9]{2}[0-9]{7}[A-Z0-9]{16}$/',
            'HU' => '/^GB[0-9]{2}[0-9]{7}[0-9]{1}[0-9]{15}[0-9]{1}$/',
            'IE' => '/^IE[0-9]{2}[A-Z0-9]{4}[0-9]{6}[0-9]{8}$/',
            'IS' => '/^IS[0-9]{2}[0-9]{4}[0-9]{18}$/',
            'IT' => '/^IT[0-9]{2}[A-Z]{1}[0-9]{10}[A-Z0-9]{12}$/',
            'LU' => '/^LU[0-9]{2}[0-9]{3}[A-Z0-9]{13}$/',
            'NL' => '/^NL[0-9]{2}[A-Z]{4}[0-9]{10}$/',
            'NO' => '/^NO[0-9]{2}[0-9]{4}[0-9]{7}$/',
            'PL' => '/^PT[0-9]{2}[0-9]{8}[A-Z0-9]{16}$/',
            'PT' => '/^PT[0-9]{2}[0-9]{8}[0-9]{13}$/',
            'SE' => '/^SE[0-9]{2}[0-9]{3}[0-9]{17}$/',
            'SI' => '/^SE[0-9]{2}[0-9]{5}[0-9]{8}[0-9]{2}$/'
        );

// {{{ error codes

/*
 * Error codes for the IBAN interface, which will be mapped to textual messages
 * in the IBAN::errorMessage() function.  If you are to add a new error code, be
 * sure to add the textual messages to the IBAN::errorMessage() function as well
 */

define('IBAN_OK',                      1);
define('IBAN_ERROR',                  -1);
define('IBAN_GENERAL_INVALID',        -2);
define('IBAN_TOO_SHORT',              -4);
define('IBAN_TOO_LONG',               -5);
define('IBAN_COUNTRY_INVALID',        -6);
define('IBAN_INVALID_FORMAT',         -7); // tested via regex; e.g. if un-allowed characters in IBAN
define('IBAN_CHECKSUM_INVALID',       -8);

// }}}

/**
* Validate and process IBAN (international bank account numbers)
*
* @author      Stefan Neufeind <neufeind@speedpartner.de>
*/
class Validate_Finance_IBAN {
    // {{{ properties

    /**
     * String containing the IBAN to be processed
     * @var  string
     * @access   private
     */
    var $_iban = '';

    /**
     * Integer containing errorcode of last validation
     * @var      integer
     * @access   private
     */
    var $_errorcode = 0;

    // }}}
    // {{{ constructor

    /**
     * Class constructor
     * @param    string      $iban              IBAN to be validated / processed
     * @access   public
     */
    function Validate_Finance_IBAN($iban='')
    {
        $iban = strtoupper($iban);
        $this->_iban = $iban;
    } // end constructor

    // }}}
    // {{{ apiVersion()

    /**
     * Returns the current API version
     *
     * @access    public
     * @return    float
     */
    function apiVersion()
    {
        return 1.0;
    } // end func apiVersion

    // }}}
    // {{{ getIBAN()

    /**
     * Returns the current IBAN
     *
     * @access    public
     * @return    string
     */
    function getIBAN()
    {
        return $this->_iban;
    } // end func setIBAN

    // }}}
    // {{{ setIBAN()

    /**
     * Sets the current IBAN to a new value
     *
     * @param    string      $iban              IBAN to be validated / processed
     * @access    public
     * @return    void
     */
    function setIBAN($iban='')
    {
        $iban = strtoupper($iban);
        $this->_iban = $iban;
    } // end func setIBAN

    // }}}
    // {{{ validate()

    /**
     * Performs validation of the IBAN
     *
     * @param     string      $arg              optional parameter for calling validate as a static function
     * @access    public
     * @return    boolean   true if no error found
     */
    function validate($arg='')
    {
        if ( isset($this) ) {
            $iban = $this->_iban;
        } else {
            $iban = $arg;
        }

        $errorcode=IBAN_OK;

        if (strlen($iban) <= 4) {
            $errorcode = IBAN_TOO_SHORT;
        } elseif (!isset( $GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODES'][ substr($iban,0,2) ] )) {
            $errorcode = IBAN_COUNTRY_INVALID;
        } elseif (strlen($iban) < $GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_LENGTH'][ substr($iban,0,2) ]) {
            $errorcode = IBAN_TOO_SHORT;
        } elseif (strlen($iban) > $GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_LENGTH'][ substr($iban,0,2) ]) {
            $errorcode = IBAN_TOO_LONG;
        } elseif (!preg_match($GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_REGEX'][ substr($iban,0,2) ],$iban)) {
            $errorcode = IBAN_INVALID_FORMAT;
        } else {
            // todo: maybe implement direct checks for bankcodes of certain countries

            // let's see if checksum is also correct
            $iban_replace_chars = range('A','Z');
            foreach (range(10,35) as $tempvalue) {
              $iban_replace_values[]=strval($tempvalue);
            }

            // move first 4 chars (countrycode and checksum) to the end of the string
            $tempiban = substr($iban, 4).substr($iban, 0, 4);
            $tempiban = str_replace($iban_replace_chars, $iban_replace_values, $tempiban);
            $tempcheckvalue = intval(substr($tempiban, 0, 1));
            for ($strcounter = 1; $strcounter < strlen($tempiban); $strcounter++) {
                $tempcheckvalue *= 10;
                $tempcheckvalue += intval(substr($tempiban,$strcounter,1));
                $tempcheckvalue %= 97;
            }

            // checkvalue of 1 indicates correct IBAN checksum
            if ($tempcheckvalue != 1) {
                $errorcode=IBAN_CHECKSUM_INVALID;
            } else {
                $errorcode=IBAN_OK;
            }
        }

        if ( isset($this) ) {
            $this->_errorcode=$errorcode;
        }
        return ($errorcode == IBAN_OK);
    } // end func validate

    // }}}

    /**
     * Returns errorcode corresponding to last validation
     *
     * @access    public
     * @return    integer    errorcode
     */
    function getErrorcode()
    {
        return $this->_errorcode;
    } // end func getErrorcode

    // {{{ getCountrycode()

    /**
     * Returns the countrycode of the IBAN
     *
     * @access    public
     * @return    string
     */
    function getCountrycode()
    {
        if (strlen($this->_iban)>4) {
            // return first two characters
            return substr($this->_iban,0,2);
        } else {
            $this->_errorcode = IBAN_TOO_SHORT;
            return PEAR::raiseError($this->errorMessage($this->_errorcode), $this->_errorcode, PEAR_ERROR_TRIGGER, E_USER_WARNING, $this->errorMessage($this->_errorcode)." in VALIDATE_FINANCE_IBAN::getCountrycode()");
        }
    } // end func getCountrycode

    // }}}
    // {{{ getBankcode()
    /**
     * Returns the bankcode of the IBAN
     *
     * @access    public
     * @return    string
     */
    function getBankcode()
    {
        if (!$this->validate()) {
            $this->_errorcode = IBAN_GENERAL_INVALID;
            return PEAR::raiseError($this->errorMessage($this->_errorcode), $this->_errorcode, PEAR_ERROR_TRIGGER, E_USER_WARNING, $this->errorMessage($this->_errorcode)." in VALIDATE_FINANCE_IBAN::getBankcode()");
        }
        else
        {
          $currCountrycodeBankcode = $GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_BANKCODE'][ substr($iban,0,2) ];
          return substr($this->_iban,$currCountrycodeBankcode['start'],$currCountrycodeBankcode['length']);
        }
    } // end func getBankcode
    // }}}

    // {{{ getBankaccount()
    /**
     * Returns the bankaccount of the IBAN
     *
     * @access    public
     * @return    string
     */
    function getBankaccount()
    {
        if (!$this->validate()) {
            $this->_errorcode = IBAN_GENERAL_INVALID;
            return PEAR::raiseError($this->errorMessage($this->_errorcode), $this->_errorcode, PEAR_ERROR_TRIGGER, E_USER_WARNING, $this->errorMessage($this->_errorcode)." in VALIDATE_FINANCE_IBAN::getBankaccount()");
        }
        else
        {
          $currCountrycodeBankcode = $GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_BANKACCOUNT'][ substr($iban,0,2) ];
          return substr($this->_iban,$currCountrycodeBankcode['start'],$currCountrycodeBankcode['length']);
        }
    } // end func getAccount

    // }}}
    // {{{ errorMessage()

    /**
     * Return a textual error message for an IBAN error code
     *
     * @access  public
     * @param   int     error code
     * @return  string  error message
     */
    function errorMessage($value)
    {
        // make the variable static so that it only has to do the defining on the first call
        static $errorMessages;

        // define the varies error messages
        if (!isset($errorMessages)) {
            $errorMessages = array(
                IBAN_OK                    => 'no error',
                IBAN_ERROR                 => 'unknown error',
                IBAN_GENERAL_INVALID       => 'IBAN generally invalid',
                IBAN_TOO_SHORT             => 'IBAN is too short',
                IBAN_TOO_LONG              => 'IBAN is too long',
                IBAN_COUNTRY_INVALID       => 'IBAN countrycode is invalid',
                IBAN_INVALID_FORMAT        => 'IBAN has invalid format',
                IBAN_CHECKSUM_INVALID      => 'IBAN checksum is invalid'
            );
        }

        // If this is an error object, then grab the corresponding error code
        if (VALIDATE_FINANCE_IBAN::isError($value)) {
            $value = $value->getCode();
        }

        // return the textual error message corresponding to the code
        return isset($errorMessages[$value]) ? $errorMessages[$value] : $errorMessages[IBAN_ERROR];
    } // end func errorMessage

    // }}}
} // end class VALIDATE_FINANCE_IBAN
?>