<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Stefan Neufeind <pear.neufeind@speedpartner.de>              |
// +----------------------------------------------------------------------+
//
// $Id$

/*
 * Error codes for the IBAN interface, which will be mapped to textual messages
 * in the IBAN::errorMessage() function.  If you are to add a new error code, be
 * sure to add the textual messages to the IBAN::errorMessage() function as well
 */

define('VALIDATE_FINANCE_IBAN_OK',                 1);
define('VALIDATE_FINANCE_IBAN_ERROR',             -1);
define('VALIDATE_FINANCE_IBAN_GENERAL_INVALID',   -2);
define('VALIDATE_FINANCE_IBAN_TOO_SHORT',         -4);
define('VALIDATE_FINANCE_IBAN_TOO_LONG',          -5);
define('VALIDATE_FINANCE_IBAN_COUNTRY_INVALID',   -6);
define('VALIDATE_FINANCE_IBAN_INVALID_FORMAT',    -7); // tested via regex; e.g. if un-allowed characters in IBAN
define('VALIDATE_FINANCE_IBAN_CHECKSUM_INVALID',  -8);

/**
* Validate and process IBAN (international bank account numbers)
*
* @author      Stefan Neufeind <neufeind@speedpartner.de>
*/
class Validate_Finance_IBAN {
    /**
     * String containing the IBAN to be processed
     * @var     string
     * @access  private
     */
    var $_iban = '';

    /**
     * Integer containing errorcode of last validation
     * @var     integer
     * @access  private
     */
    var $_errorcode = 0;
    
    /**
     * List of all IBAN countrycodes; also gives corresponding countrynames (in long form)
     * @return  array
     * @access  private
     */
    function _getCountrycodeCountryname()
    {
        static $_iban_countrycode_countryname;
        if (!isset($_iban_countrycode_countryname)) {
            $_iban_countrycode_countryname =
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
        }
        return $_iban_countrycode_countryname;
    }

    /**
     * List of IBAN length; can be used for a quick check
     * @return  array
     * @access  private
     */
    function _getCountrycodeIBANLength()
    {
        static $_iban_countrycode_length;
        if (!isset($_iban_countrycode_length)) {
            $_iban_countrycode_length =
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
        }
        return $_iban_countrycode_length;
    }

    /**
     * List of where the bankcode inside an IBAN starts (starting from 0) and its length
     * @return  array
     * @access  private
     */
    function _getCountrycodeBankcode()
    {
        static $_iban_countrycode_bankcode;
        if (!isset($_iban_countrycode_bankcode)) {
            $_iban_countrycode_bankcode =
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
        }
        return $_iban_countrycode_bankcode;
    }

    /**
     * List of where the bankaccount-number inside an IBAN starts (starting from 0) and its length
     * @return  array
     * @access  private
     */
    function _getCountrycodeBankaccount()
    {
        static $_iban_countrycode_bankaccount;
        if (!isset($_iban_countrycode_bankaccount)) {
            $_iban_countrycode_bankaccount =
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
        }
        return $_iban_countrycode_bankaccount;
    }

    /**
     * List of regex for validating an IBAN according to standards for each country
     * @return  array
     * @access  private
     */
    function _getCountrycodeRegex()
    {
        static $_iban_countrycode_regex;
        if (!isset($_iban_countrycode_regex)) {
            $_iban_countrycode_regex =
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
                    'PL' => '/^PL[0-9]{2}[0-9]{8}[0-9]{16}$/',
                    'PT' => '/^PT[0-9]{2}[0-9]{8}[0-9]{13}$/',
                    'SE' => '/^SE[0-9]{2}[0-9]{3}[0-9]{17}$/',
                    'SI' => '/^SE[0-9]{2}[0-9]{5}[0-9]{8}[0-9]{2}$/'
                );
        }
        return $_iban_countrycode_regex;
    }

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

    /**
     * Returns the current IBAN
     *
     * @access    public
     * @return    string
     */
    function getIBAN()
    {
        return $this->_iban;
    } // end func getIBAN

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

    /**
     * Performs validation of the IBAN
     *
     * @param     string      $arg              optional parameter for calling validate as a static function
     * @access    public
     * @return    boolean   true if no error found
     */
    function validate($arg=null)
    {
        if ( isset($this) && is_a($this, 'Validate_Finance_IBAN') ) {
            $iban = $this->_iban;
        } else {
            $iban = $arg;
        }

        $errorcode=VALIDATE_FINANCE_IBAN_OK;

        static $_iban_countrycode_countryname;
        if (!isset($_iban_countrycode_countryname)) {
            $_iban_countrycode_countryname = Validate_Finance_IBAN::_getCountrycodeCountryname();
        }
        static $_iban_countrycode_length;
        if (!isset($_iban_countrycode_length)) {
            $_iban_countrycode_ibanlength      = Validate_Finance_IBAN::_getCountrycodeIBANLength();
        }
        static $_iban_countrycode_regex;
        if (!isset($_iban_countrycode_regex)) {
            $_iban_countrycode_regex       = Validate_Finance_IBAN::_getCountrycodeRegex();
        }
        
        if (strlen($iban) <= 4) {
            $errorcode = VALIDATE_FINANCE_IBAN_TOO_SHORT;
        } elseif (!isset( $_iban_countrycode_countryname[ substr($iban,0,2) ] )) {
            $errorcode = VALIDATE_FINANCE_IBAN_COUNTRY_INVALID;
        } elseif (strlen($iban) < $_iban_countrycode_ibanlength[ substr($iban,0,2) ]) {
            $errorcode = VALIDATE_FINANCE_IBAN_TOO_SHORT;
        } elseif (strlen($iban) > $_iban_countrycode_ibanlength[ substr($iban,0,2) ]) {
            $errorcode = VALIDATE_FINANCE_IBAN_TOO_LONG;
        } elseif (!preg_match($_iban_countrycode_regex[ substr($iban,0,2) ],$iban)) {
            $errorcode = VALIDATE_FINANCE_IBAN_INVALID_FORMAT;
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
                $errorcode=VALIDATE_FINANCE_IBAN_CHECKSUM_INVALID;
            } else {
                $errorcode=VALIDATE_FINANCE_IBAN_OK;
            }
        }

        if ( isset($this) ) {
            $this->_errorcode=$errorcode;
        }
        return ($errorcode == VALIDATE_FINANCE_IBAN_OK);
    } // end func validate

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
            $this->_errorcode = VALIDATE_FINANCE_IBAN_TOO_SHORT;
            return PEAR::raiseError($this->errorMessage($this->_errorcode), $this->_errorcode, PEAR_ERROR_TRIGGER, E_USER_WARNING, $this->errorMessage($this->_errorcode)." in VALIDATE_FINANCE_IBAN::getCountrycode()");
        }
    } // end func getCountrycode

    /**
     * Returns the countryname of the IBAN
     *
     * @access    public
     * @return    string
     */
    function getCountryname()
    {
        $countrycode = $this->getCountrycode();
        if (is_string($countrycode)) {
            $_iban_countrycode_countryname = Validate_Finance_IBAN::_getCountrycodeCountryname();
            return $_iban_countrycode_countryname[$countrycode];
        } else { // e.g. if it's an error
            return $countrycode;
        }
    } // end func getCountryname

    /**
     * Returns the bankcode of the IBAN
     *
     * @access    public
     * @return    string
     */
    function getBankcode()
    {
        if (!$this->validate()) {
            $this->_errorcode = VALIDATE_FINANCE_IBAN_GENERAL_INVALID;
            return PEAR::raiseError($this->errorMessage($this->_errorcode), $this->_errorcode, PEAR_ERROR_TRIGGER, E_USER_WARNING, $this->errorMessage($this->_errorcode)." in VALIDATE_FINANCE_IBAN::getBankcode()");
        } else {
            $_iban_countrycode_bankcode = Validate_Finance_IBAN::_getCountrycodeBankcode();
            $currCountrycodeBankcode = $_iban_countrycode_bankcode[ substr($this->_iban,0,2) ];
            return substr($this->_iban, $currCountrycodeBankcode['start'], $currCountrycodeBankcode['length']);
        }
    } // end func getBankcode

    /**
     * Returns the bankaccount of the IBAN
     *
     * @access    public
     * @return    string
     */
    function getBankaccount()
    {
        if (!$this->validate()) {
            $this->_errorcode = VALIDATE_FINANCE_IBAN_GENERAL_INVALID;
            return PEAR::raiseError($this->errorMessage($this->_errorcode), $this->_errorcode, PEAR_ERROR_TRIGGER, E_USER_WARNING, $this->errorMessage($this->_errorcode)." in VALIDATE_FINANCE_IBAN::getBankaccount()");
        } else {
            $_iban_countrycode_bankaccount = Validate_Finance_IBAN::_getCountrycodeBankaccount();
            $currCountrycodeBankaccount = $_iban_countrycode_bankaccount[ substr($iban,0,2) ];
            return substr($this->_iban, $currCountrycodeBankaccount['start'], $currCountrycodeBankaccount['length']);
        }
    } // end func getAccount

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
                VALIDATE_FINANCE_IBAN_OK                => 'no error',
                VALIDATE_FINANCE_IBAN_ERROR             => 'unknown error',
                VALIDATE_FINANCE_IBAN_GENERAL_INVALID   => 'IBAN generally invalid',
                VALIDATE_FINANCE_IBAN_TOO_SHORT         => 'IBAN is too short',
                VALIDATE_FINANCE_IBAN_TOO_LONG          => 'IBAN is too long',
                VALIDATE_FINANCE_IBAN_COUNTRY_INVALID   => 'IBAN countrycode is invalid',
                VALIDATE_FINANCE_IBAN_INVALID_FORMAT    => 'IBAN has invalid format',
                VALIDATE_FINANCE_IBAN_CHECKSUM_INVALID  => 'IBAN checksum is invalid'
            );
        }

        // If this is an error object, then grab the corresponding error code
        if (VALIDATE_FINANCE_IBAN::isError($value)) {
            $value = $value->getCode();
        }

        // return the textual error message corresponding to the code
        return isset($errorMessages[$value]) ? $errorMessages[$value] : $errorMessages[VALIDATE_FINANCE_IBAN_ERROR];
    } // end func errorMessage
} // end class VALIDATE_FINANCE_IBAN
?>