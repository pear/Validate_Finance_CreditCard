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

$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODES'] = 
        array(
            'BE' => 'Belgium',
            'DK' => 'Denmark',
            'FI' => 'Finland',
            'FR' => 'France',
            'GB' => 'Great Britain',
            'IE' => 'Ireland',
            'IS' => 'Island',
            'IT' => 'Italy',
            'LU' => 'Luxembourg',
            'NL' => 'The Netherlands',
            'NO' => 'Norwegian',
            'AT' => 'Austria',
            'PT' => 'Portugal',
            'SE' => 'Sweden',
            'CH' => 'Swiss',
            'ES' => 'Spain',
            'DE' => 'Germany'
        );
        
    /**
     * List of how many chars (counted from 5th char of the IBAN) represent the bankcode
     * A value of 0 means that bankcode is part of the bankaccounts
     * @since     0.1
     * @var  array
     */
$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_BANKCODE_CHARS'] = 
        array(
            'BE' =>  0,
            'DK' =>  0,
            'FI' =>  0,
            'FR' => 10,
            'GB' =>  6,
            'IE' =>  6,
            'IS' =>  4,
            'IT' =>  9,
            'LU' =>  0,
            'NL' =>  0,
            'NO' =>  0,
            'AT' =>  5,
            'PT' =>  8,
            'SE' =>  0,
            'CH' =>  6,
            'ES' =>  8,
            'DE' =>  8
        );         
        
$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_REGEX'] = 
        array(
            'BE' => '/^BE[0-9]{2}[A-Z0-9]+$/',              // bankcode inside bankaccount
                                                            // todo: check which chars are allowed in bankaccounts
            'DK' => '/^DK[0-9]{2}[A-Z0-9]+$/',              // bankcode inside bankaccount
                                                            // todo: check which chars are allowed in bankaccounts
            'FI' => '/^FI[0-9]{2}[A-Z0-9]+$/',              // bankcode inside bankaccount
                                                            // todo: check which chars are allowed in bankaccounts
            'FR' => '/^FR[0-9]{2}[A-Z0-9]{10}[A-Z0-9]+$/',  // bankcode: 10 chars
                                                            // todo: check which chars are allowed in bankcode/bankaccounts
            'GB' => '/^GB[0-9]{2}[A-Z0-9]{6}[A-Z0-9]+$/',   // bankcode:  6 chars
                                                            // todo: check which chars are allowed in bankcode/bankaccounts
            'IE' => '/^IE[0-9]{2}[A-Z0-9]{6}[A-Z0-9]+$/',   // bankcode:  6 chars
                                                            // todo: check which chars are allowed in bankcode/bankaccounts
            'IS' => '/^IS[0-9]{2}[A-Z0-9]{4}[A-Z0-9]+$/',   // bankcode:  4 chars
                                                            // todo: check which chars are allowed in bankcode/bankaccounts
            'IT' => '/^IT[0-9]{2}[A-Z0-9]{9}[A-Z0-9]+$/',   // bankcode:  9 chars
                                                            // todo: check which chars are allowed in bankcode/bankaccounts
            'LU' => '/^LU[0-9]{2}[A-Z0-9]+$/',              // bankcode inside bankaccount
                                                            // todo: check which chars are allowed in bankaccounts
            'NL' => '/^NL[0-9]{2}[A-Z0-9]+$/',              // bankcode inside bankaccount
                                                            // todo: check which chars are allowed in bankaccounts
            'NO' => '/^NO[0-9]{2}[A-Z0-9]+$/',              // bankcode inside bankaccount
                                                            // todo: check which chars are allowed in bankaccounts
            'AT' => '/^AT[0-9]{2}[0-9]{5}[0-9]+$/',         // bankcode:  5 chars
                                                            // todo: check which chars are allowed in bankaccounts
            'PT' => '/^PT[0-9]{2}[A-Z0-9]{8}[A-Z0-9]+$/',   // bankcode:  8 chars
                                                            // todo: check which chars are allowed in bankcode/bankaccounts
            'SE' => '/^SE[0-9]{2}[A-Z0-9]+$/',              // bankcode inside bankaccount
                                                            // todo: check which chars are allowed in bankaccounts
            'CH' => '/^CH[0-9]{2}[A-Z0-9]{6}[A-Z0-9]+$/',   // bankcode:  6 chars
                                                            // todo: check which chars are allowed in bankcode/bankaccounts
            'ES' => '/^ES[0-9]{2}[A-Z0-9]{8}[A-Z0-9]+$/',   // bankcode:  8 chars
                                                            // todo: check which chars are allowed in bankcode/bankaccounts
            'DE' => '/^DE[0-9]{2}[0-9]{8}[0-9]+$/'          // bankcode:  8 chars
                                                            // todo: check which chars are allowed in bankaccounts
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
define('IBAN_COUNTRY_INVALID',        -5);
define('IBAN_INVALID_FORMAT',         -6); // tested via regex; e.g. if un-allowed characters in IBAN
define('IBAN_CHECKSUM_INVALID',       -7);

// }}}

/**
* Validate and process IBAN (international bank account numbers)
*
* @author      Stefan Neufeind <neufeind@speedpartner.de>
* @version     0.1
* @since       PHP 4.1.0
*/
class Validate_Finance_IBAN {
    // {{{ properties

    /**
     * String containing the IBAN to be processed
     * @since     0.1
     * @var  string
     * @access   private
     */
    var $_iban = '';

    /**
     * Integer containing errorcode of last validation
     * @since    0.1
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
     * @since     0.1
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
     * @since     0.1
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
     * @since     0.1
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
     * @since     0.1
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
            $errorcode=IBAN_TOO_SHORT;
        } elseif (!isset( $GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODES'][ substr($iban,0,2) ] )) {
            $errorcode=IBAN_COUNTRY_INVALID;
        } elseif (strlen($iban) <= (4+$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_BANKCODE_CHARS'][ substr($iban,0,2) ])) {
            $errorcode=IBAN_TOO_SHORT;
        } elseif (!preg_match($GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_REGEX'][ substr($iban,0,2) ],$iban)) {
            $errorcode=IBAN_INVALID_FORMAT;
        } else {
            // todo: maybe implement direct checks for bankcodes of certain countries

            // let's see if checksum is also correct
            $iban_replace_chars=range('A','Z');
            foreach (range(10,35) as $tempvalue)
              $iban_replace_values[]=strval($tempvalue);
            
            // move first 4 chars (countrycode and checksum) to the end of the string
            $tempiban = substr($iban,4).substr($iban,0,4);
            $tempiban = str_replace($iban_replace_chars, $iban_replace_values, $tempiban);
            $tempcheckvalue = intval(substr($tempiban,0,1));
            for ($strcounter=1;$strcounter<strlen($tempiban);$strcounter++) {
                $tempcheckvalue *= 10;
                $tempcheckvalue += intval(substr($tempiban,$strcounter,1));
                $tempcheckvalue %= 97;
            }
            
            // checkvalue of 1 indicates correct IBAN checksum
            if ($tempcheckvalue != 1) {
                $errorcode=IBAN_CHECKSUM_INVALID;
            } else {
                $errorcode=IBAN_OK;
                return true;
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
     * @since     0.1
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
     * @since     0.1
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
     * @since     0.1
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
          return substr($this->_iban,4,$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_BANKCODE_CHARS'][ substr($this->_iban,0,2) ]);
    } // end func getBankcode
    // }}}
    
    // {{{ getBankaccount()
    /**
     * Returns the bankaccount of the IBAN
     *
     * @since     0.1
     * @access    public
     * @return    string
     */
    function getBankaccount()
    {
        if (!$this->validate()) {
            $this->_errorcode = IBAN_GENERAL_INVALID;
            return PEAR::raiseError($this->errorMessage($this->_errorcode), $this->_errorcode, PEAR_ERROR_TRIGGER, E_USER_WARNING, $this->errorMessage($this->_errorcode)." in VALIDATE_FINANCE_IBAN::getBankaccount()");
        } else {
            return substr($this->_iban,4+$GLOBALS['VALIDATE_FINANCE_IBAN_COUNTRYCODE_BANKCODE_CHARS'][ substr($this->_iban,0,2) ]);
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