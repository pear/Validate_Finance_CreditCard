<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Financial functions for validation and calculation
 *
 * PHP Versions 4 and 5
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
 * @author    Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @copyright 1997-2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/Validate_Finance
 */

// needed for call to isError()
require_once 'PEAR.php';

/*
 * Error codes for the IBAN interface, which will be mapped to textual messages
 * in the IBAN::errorMessage() function.  If you are to add a new error code, be
 * sure to add the textual messages to the IBAN::errorMessage() function as well
 */

define('VALIDATE_FINANCE_IBAN_OK', 1);
define('VALIDATE_FINANCE_IBAN_ERROR', -1);
define('VALIDATE_FINANCE_IBAN_GENERAL_INVALID', -2);
define('VALIDATE_FINANCE_IBAN_TOO_SHORT', -4);
define('VALIDATE_FINANCE_IBAN_TOO_LONG', -5);
define('VALIDATE_FINANCE_IBAN_COUNTRY_INVALID', -6);
// tested via regex; e.g. if un-allowed characters in IBAN
define('VALIDATE_FINANCE_IBAN_INVALID_FORMAT', -7);
define('VALIDATE_FINANCE_IBAN_CHECKSUM_INVALID', -8);

/**
 * Validate and process IBAN (international bank account numbers)
 *
 * @category  File_Formats
 * @package   Validate_Finance
 * @author    Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @copyright 1997-2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://pear.php.net/package/Validate_Finance
 */
class Validate_Finance_IBAN
{
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
     * List of all IBAN countrycodes; also gives corresponding countrynames (in
     * long form)
     *
     * @return  array
     * @access  private
     */
    function _getCountrycodeCountryname()
    {
        static $_iban_countrycode_countryname;
        if (!isset($_iban_countrycode_countryname)) {
            $_iban_countrycode_countryname = array(
                'AD' => 'Andorra',
                'AE' => 'United Arab Emirates',
                'AL' => 'Albania',
                'AT' => 'Austria',
                'BA' => 'Bosnia and Herzegovina',
                'BE' => 'Belgium',
                'BG' => 'Bulgaria',
                'CH' => 'Switzerland',
                'CY' => 'Cyprus',
                'CZ' => 'Czech Republic',
                'DE' => 'Germany',
                'DK' => 'Denmark',
                'EE' => 'Estonia',
                'ES' => 'Spain',
                'FR' => 'France',
                'FI' => 'Finland',
                'GB' => 'United Kingdom',
                'GE' => 'Georgia',
                'GI' => 'Gibraltar',
                'GR' => 'Greece',
                'HR' => 'Croatia',
                'HU' => 'Hungary',
                'IE' => 'Ireland',
                'IL' => 'Israel',
                'IS' => 'Iceland',
                'IT' => 'Italy',
                'KW' => 'Kuwait',
                'LB' => 'Lebanon',
                'LI' => 'Liechtenstein',
                'LT' => 'Lithuania',
                'LU' => 'Luxembourg',
                'LV' => 'Latvia',
                'MC' => 'Monaco',
                'ME' => 'Montenegro',
                'MK' => 'Macedonia',
                'MR' => 'Mauritania',
                'MT' => 'Malta',
                'MU' => 'Mauritius',
                'NL' => 'The Netherlands',
                'NO' => 'Norwegian',
                'PL' => 'Poland',
                'PT' => 'Portugal',
                'RO' => 'Romania',
                'RS' => 'Serbia',
                'SA' => 'Saudi Arabia',
                'SE' => 'Sweden',
                'SI' => 'Slovenia',
                'SK' => 'Slovak Republic',
                'SM' => 'San Marino',
                'TN' => 'Tunisia',
                'TR' => 'Turkey',
            );
        }
        return $_iban_countrycode_countryname;
    }

    /**
     * List of IBAN length; can be used for a quick check
     *
     * @return  array
     * @access  private
     */
    function _getCountrycodeIBANLength()
    {
        static $_iban_countrycode_length;
        if (!isset($_iban_countrycode_length)) {
            $_iban_countrycode_length = array(
                'AD' => 24,
                'AE' => 23,
                'AL' => 28,
                'AT' => 20,
                'BA' => 20,
                'BE' => 16,
                'BG' => 22,
                'CH' => 21,
                'CY' => 28,
                'CZ' => 24,
                'DE' => 22,
                'DK' => 18,
                'EE' => 20,
                'ES' => 24,
                'FR' => 27,
                'FI' => 18,
                'GB' => 22,
                'GE' => 22,
                'GI' => 23,
                'GR' => 27,
                'HR' => 21,
                'HU' => 28,
                'IE' => 22,
                'IL' => 23,
                'IS' => 26,
                'IT' => 27,
                'KW' => 30,
                'LB' => 28,
                'LI' => 21,
                'LT' => 20,
                'LU' => 20,
                'LV' => 21,
                'MC' => 27,
                'ME' => 22,
                'MK' => 19,
                'MR' => 27,
                'MT' => 31,
                'MU' => 30,
                'NL' => 18,
                'NO' => 15,
                'PL' => 28,
                'PT' => 25,
                'RO' => 24,
                'RS' => 22,
                'SA' => 24,
                'SE' => 24,
                'SI' => 19,
                'SK' => 24,
                'SM' => 27,
                'TN' => 24,
                'TR' => 26,
            );
        }
        return $_iban_countrycode_length;
    }

    /**
     * List of where the bankcode inside an IBAN starts (starting from 0) and
     * its length
     *
     * @return  array
     * @access  private
     */
    function _getCountrycodeBankcode()
    {
        static $_iban_countrycode_bankcode;
        if (!isset($_iban_countrycode_bankcode)) {
            $_iban_countrycode_bankcode = array(
                //AD: first 4 chars bankcode, last 4 chars branch
                'AD' => array('start' =>  4, 'length' =>  8),
                'AE' => array('start' =>  4, 'length' =>  3),
                //AL: first 3 chars bankcode, next 4 chars branch, one char checksum
                'AL' => array('start' =>  4, 'length' =>  8),
                'AT' => array('start' =>  4, 'length' =>  5),
                //BA: first 3 chars bankcode, last 3 chars branch
                'BA' => array('start' =>  4, 'length' =>  6),
                'BE' => array('start' =>  4, 'length' =>  3),
                'BG' => array('start' =>  4, 'length' =>  8),
                'CH' => array('start' =>  4, 'length' =>  5),
                //CY: first 3 chars bankcode, last 5 chars branch
                'CY' => array('start' =>  4, 'length' =>  8),
                'CZ' => array('start' =>  4, 'length' =>  4),
                'DE' => array('start' =>  4, 'length' =>  8),
                'DK' => array('start' =>  4, 'length' =>  4),
                //EE: first 2 chars bankidentifier, last 2 chars bankcode
                'EE' => array('start' =>  4, 'length' =>  4),
                // ES: followed by 2 chars (checksum)
                'ES' => array('start' =>  4, 'length' =>  8),
                'FR' => array('start' =>  4, 'length' => 10),
                'FI' => array('start' =>  4, 'length' =>  6),
                //GB: first 4 chars bankidentifier, last 6 chars bank-branchcode
                'GB' => array('start' =>  4, 'length' => 10),
                'GE' => array('start' =>  4, 'length' =>  2),
                'GI' => array('start' =>  4, 'length' =>  4),
                //GR: first 3 chars bankcode, last 4 chars branch
                'GR' => array('start' =>  4, 'length' =>  7),
                'HR' => array('start' =>  4, 'length' =>  7),
                //HU: first 3 chars bankcode, last 4 chars branch,
                //followed by 1 char (checksum)
                'HU' => array('start' =>  4, 'length' =>  7),
                //IE: first 4 chars bankcode, last 6 chars branch
                'IE' => array('start' =>  4, 'length' => 10),
                //IL: first 3 chars bankcode, last 3 chars branch
                'IL' => array('start' =>  4, 'length' =>  6),
                'IS' => array('start' =>  4, 'length' =>  4),
                'IT' => array('start' =>  4, 'length' => 11),
                'KW' => array('start' =>  4, 'length' =>  4),
                'LB' => array('start' =>  4, 'length' =>  4),
                //LI: bankcode and branch
                'LI' => array('start' =>  4, 'length' =>  5),
                'LT' => array('start' =>  4, 'length' =>  5),
                'LU' => array('start' =>  4, 'length' =>  3),
                'LV' => array('start' =>  4, 'length' =>  4),
                //MC: first 5 chars bankcode, last 5 chars branch
                'MC' => array('start' =>  4, 'length' =>  10),
                'ME' => array('start' =>  4, 'length' =>  3),
                'MK' => array('start' =>  4, 'length' =>  3),
                //MR: first 5 chars bankcode, last 5 chars branch
                'M$' => array('start' =>  4, 'length' => 10),
                //MT: first 4 chars bankcode, last 5 chars bank sort code
                'MT' => array('start' =>  4, 'length' =>  9),
                //MU: first 6 chars bankcode, last 2 chars branch
                'MU' => array('start' =>  4, 'length' =>  8),
                'NL' => array('start' =>  4, 'length' =>  4),
                'NO' => array('start' =>  4, 'length' =>  4),
                'PL' => array('start' =>  4, 'length' =>  8),
                'PT' => array('start' =>  4, 'length' =>  8),
                'RO' => array('start' =>  4, 'length' =>  4),
                'RS' => array('start' =>  4, 'length' =>  3),
                'SA' => array('start' =>  4, 'length' =>  2),
                //SE: bankcode and branch
                'SE' => array('start' =>  4, 'length' =>  3),
                'SI' => array('start' =>  4, 'length' =>  5),
                'SK' => array('start' =>  4, 'length' =>  4),
                //SM: starts with one char, followed by 5 chars bankcode, last 5 chars branch
                'SM' => array('start' =>  5, 'length' =>  10),
                //TN: first 2 chars bankcode, last 3 chars branch
                'TN' => array('start' =>  4, 'length' =>  5),
                //TR: followed by 1 char (reserved field)
                'TR' => array('start' =>  4, 'length' =>  5),
            );
        }
        return $_iban_countrycode_bankcode;
    }

    /**
     * List of where the bankaccount-number inside an IBAN starts (starting from 0)
     * and its length
     *
     * @return  array
     * @access  private
     */
    function _getCountrycodeBankaccount()
    {
        static $_iban_countrycode_bankaccount;
        if (!isset($_iban_countrycode_bankaccount)) {
            $_iban_countrycode_bankaccount = array(
                'AD' => array('start' => 12, 'length' => 12),
                'AE' => array('start' =>  7, 'length' => 23),
                'AL' => array('start' => 12, 'length' => 16),
                'AT' => array('start' =>  9, 'length' => 11),
                //BA: followed by 2 chars (checksum)
                'BA' => array('start' => 10, 'length' =>  8),
                //BE: followed by 2 chars (checksum)
                'BE' => array('start' =>  7, 'length' =>  7),
                'BG' => array('start' => 12, 'length' => 10),
                'CH' => array('start' =>  9, 'length' => 12),
                'CY' => array('start' => 12, 'length' => 16),
                'CZ' => array('start' =>  8, 'length' => 16),
                'DE' => array('start' => 12, 'length' => 10),
                //DK: followed by 1 char (checksum)
                'DK' => array('start' =>  8, 'length' =>  9),
                //EE: followed by 1 char (checksum)
                'EE' => array('start' =>  8, 'length' => 11),
                'ES' => array('start' => 14, 'length' => 10),
                //FR: followed by 2 chars (checksum)
                'FR' => array('start' => 14, 'length' => 11),
                //FI: followed by 1 char (checksum)
                'FI' => array('start' => 10, 'length' =>  7),
                'GB' => array('start' => 14, 'length' =>  8),
                'GE' => array('start' =>  6, 'length' => 16),
                'GI' => array('start' =>  8, 'length' => 15),
                'GR' => array('start' => 11, 'length' => 16),
                'HR' => array('start' => 11, 'length' => 10),
                //HU: followed by 1 char (checksum)
                'HU' => array('start' => 12, 'length' => 15),
                'IE' => array('start' => 14, 'length' =>  8),
                'IL' => array('start' => 10, 'length' => 13),
                //IS: 2 accounttype, 6 account number, 10 identification number
                'IS' => array('start' =>  8, 'length' => 18),
                'IT' => array('start' => 15, 'length' => 12),
                'KW' => array('start' =>  8, 'length' => 22),
                'LB' => array('start' =>  8, 'length' => 20),
                'LI' => array('start' =>  9, 'length' => 12),
                'LT' => array('start' =>  9, 'length' => 11),
                'LU' => array('start' =>  7, 'length' => 13),
                'LV' => array('start' =>  8, 'length' => 13),
                'MC' => array('start' => 14, 'length' => 13),
                'ME' => array('start' =>  7, 'length' => 15),
                //MK: followed by 2 chars (checksum)
                'MK' => array('start' =>  7, 'length' => 10),
                //MR: followed by 2 chars (checksum?)
                'MR' => array('start' => 14, 'length' => 13), 
                'MT' => array('start' => 13, 'length' => 18),
                'MU' => array('start' => 12, 'length' => 18),
                'NL' => array('start' =>  8, 'length' => 10),
                //NO: followed by 1 char (checksum)
                'NO' => array('start' =>  8, 'length' =>  6),
                'PL' => array('start' => 12, 'length' => 16),
                //PT: followed by 2 chars (checksum)
                'PT' => array('start' => 12, 'length' => 11),
                // branch and client account identifier
                'RO' => array('start' =>  8, 'length' => 16),
                'RS' => array('start' =>  7, 'length' => 15),
                'SA' => array('start' =>  6, 'length' => 18),
                //SE: followed by 1 char (checksum)
                'SE' => array('start' =>  7, 'length' => 16),
                //SI: followed by 2 chars (checksum)
                'SI' => array('start' =>  9, 'length' =>  8),
                'SK' => array('start' =>  8, 'length' => 16),
                'SM' => array('start' => 15, 'length' => 12),
                //TN: followed by 2 chars (checksum)
                'TN' => array('start' =>  9, 'length' => 13),
                'TR' => array('start' => 10, 'length' => 16),
            );
        }
        return $_iban_countrycode_bankaccount;
    }

    /**
     * List of regex for validating an IBAN according to standards for each country
     *
     * @return  array
     * @access  private
     */
    function _getCountrycodeRegex()
    {
        static $_iban_countrycode_regex;
        if (!isset($_iban_countrycode_regex)) {
            $_iban_countrycode_regex = array(
                'AD' => '/^AD[0-9]{2}[0-9]{8}[A-Z0-9]{12}$/',
                'AE' => '/^AE[0-9]{2}[0-9]{3}[0-9]{16}$/',
                'AL' => '/^AL[0-9]{2}[0-9]{8}[A-Z0-9]{16}$/',
                'AT' => '/^AT[0-9]{2}[0-9]{5}[0-9]{11}$/',
                'BA' => '/^BA[0-9]{2}[0-9]{6}[0-9]{10}$/',
                'BE' => '/^BE[0-9]{2}[0-9]{3}[0-9]{9}$/',
                'BG' => '/^BG[0-9]{2}[A-Z]{4}[0-9]{4}[0-9]{2}[A-Z0-9]{8}$/',
                'CH' => '/^CH[0-9]{2}[0-9]{5}[A-Z0-9]{12}$/',
                'CY' => '/^CY[0-9]{2}[0-9]{8}[A-Z0-9]{16}$/',
                'CZ' => '/^CZ[0-9]{2}[0-9]{4}[0-9]{16}$/',
                'DE' => '/^DE[0-9]{2}[0-9]{8}[0-9]{10}$/',
                'DK' => '/^DK[0-9]{2}[0-9]{4}[0-9]{10}$/',
                'EE' => '/^EE[0-9]{2}[0-9]{4}[0-9]{12}$/',
                'ES' => '/^ES[0-9]{2}[0-9]{8}[0-9]{12}$/',
                'FR' => '/^FR[0-9]{2}[0-9]{10}[A-Z0-9]{13}$/',
                'FI' => '/^FI[0-9]{2}[0-9]{6}[0-9]{8}$/',
                'GB' => '/^GB[0-9]{2}[A-Z]{4}[0-9]{14}$/',
                'GE' => '/^GE[0-9]{2}[A-Z]{2}[0-9]{16}$/',
                'GI' => '/^GI[0-9]{2}[A-Z]{4}[A-Z0-9]{15}$/',
                'GR' => '/^GR[0-9]{2}[0-9]{7}[A-Z0-9]{16}$/',
                'HR' => '/^HR[0-9]{2}[0-9]{7}[0-9]{10}$/',
                'HU' => '/^HU[0-9]{2}[0-9]{7}[0-9]{1}[0-9]{15}[0-9]{1}$/',
                'IE' => '/^IE[0-9]{2}[A-Z0-9]{4}[0-9]{6}[0-9]{8}$/',
                'IL' => '/^IL[0-9]{2}[0-9]{6}[0-9]{13}$/',
                'IS' => '/^IS[0-9]{2}[0-9]{4}[0-9]{18}$/',
                'IT' => '/^IT[0-9]{2}[A-Z]{1}[0-9]{10}[A-Z0-9]{12}$/',
                'KW' => '/^KW[0-9]{2}[A-Z]{4}[A-Z0-9]{22}$/',
                'LB' => '/^LB[0-9]{2}[0-9]{4}[A-Z0-9]{20}$/',
                'LI' => '/^LI[0-9]{2}[0-9]{5}[A-Z0-9]{12}$/',
                'LU' => '/^LU[0-9]{2}[0-9]{3}[A-Z0-9]{13}$/',
                'LT' => '/^LT[0-9]{2}[0-9]{5}[0-9]{11}$/',
                'LV' => '/^LV[0-9]{2}[A-Z]{4}[A-Z0-9]{13}$/',
                'MC' => '/^MC[0-9]{2}[0-9]{10}[A-Z0-9]{11}[0-9]{2}$/',
                'ME' => '/^ME[0-9]{2}[0-9]{3}[0-9]{15}$/',
                'MK' => '/^MK[0-9]{2}[0-9]{3}[A-Z0-9]{10}[0-9]{2}$/',
                'MR' => '/^MR[0-9]{2}[0-9]{10}[0-9]{13}$/',
                'MT' => '/^MT[0-9]{2}[A-Z]{4}[0-9]{5}[A-Z0-9]{18}$/',
                'MU' => '/^MU[0-9]{2}[A-Z]{4}[0-9]{4}[0-9]{15}[A-Z]{3}$/',
                'NL' => '/^NL[0-9]{2}[A-Z]{4}[0-9]{10}$/',
                'NO' => '/^NO[0-9]{2}[0-9]{4}[0-9]{7}$/',
                'PL' => '/^PL[0-9]{2}[0-9]{8}[0-9]{16}$/',
                'PT' => '/^PT[0-9]{2}[0-9]{8}[0-9]{13}$/',
                'RO' => '/^RO[0-9]{2}[A-Z]{4}[A-Z0-9]{16}$/',
                'RS' => '/^RS[0-9]{2}[0-9]{3}[0-9]{15}$/',
                'SA' => '/^SA[0-9]{2}[0-9]{2}[A-Z0-9]{18}$/',
                'SE' => '/^SE[0-9]{2}[0-9]{3}[0-9]{17}$/',
                'SI' => '/^SI[0-9]{2}[0-9]{5}[0-9]{8}[0-9]{2}$/',
                'SK' => '/^SK[0-9]{2}[0-9]{4}[0-9]{16}$/',
                'SM' => '/^SM[0-9]{2}[A-Z]{1}[0-9]{10}[A-Z0-9]{12}$/',
                'TN' => '/^TN[0-9]{2}[0-9]{5}[0-9]{15}$/',
                'TR' => '/^TR[0-9]{2}[0-9]{5}[A-Z0-9]{17}$/',
             );
        }
        return $_iban_countrycode_regex;
    }

    /**
     * Class constructor
     *
     * @param string $iban IBAN to be validated / processed
     *
     * @access   public
     */
    function Validate_Finance_IBAN($iban = '')
    {
        $iban        = strtoupper($iban);
        $this->_iban = $iban;
    }

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
     * @param string $iban IBAN to be validated / processed
     *
     * @access    public
     * @return    void
     */
    function setIBAN($iban = '')
    {
        $iban        = strtoupper($iban);
        $this->_iban = $iban;
    }

    /**
     * Performs validation of the IBAN
     *
     * @param string $arg optional parameter for calling validate as static function
     *
     * @access    public
     * @return    boolean   true if no error found
     */
    function validate($arg = null)
    {
        if (isset($this) && is_a($this, 'Validate_Finance_IBAN')) {
            $iban = $this->_iban;
        } else {
            $iban = $arg;
        }
        $iban = strtoupper($iban);

        $errorcode = VALIDATE_FINANCE_IBAN_OK;

        static $_iban_countrycode_countryname;
        if (!isset($_iban_countrycode_countryname)) {
            $_iban_countrycode_countryname = Validate_Finance_IBAN::_getCountrycodeCountryname();
        }
        static $_iban_countrycode_length;
        if (!isset($_iban_countrycode_length)) {
            $_iban_countrycode_ibanlength = Validate_Finance_IBAN::_getCountrycodeIBANLength();
        }
        static $_iban_countrycode_regex;
        if (!isset($_iban_countrycode_regex)) {
            $_iban_countrycode_regex = Validate_Finance_IBAN::_getCountrycodeRegex();
        }

        // 2 character abbreviation at start of IBAN - the country code
        $abbrev = substr($iban, 0, 2);

        if (strlen($iban) <= 4) {
            $errorcode = VALIDATE_FINANCE_IBAN_TOO_SHORT;
        } elseif (!isset( $_iban_countrycode_countryname[$abbrev])) {
            $errorcode = VALIDATE_FINANCE_IBAN_COUNTRY_INVALID;
        } elseif (strlen($iban) < $_iban_countrycode_ibanlength[$abbrev]) {
            $errorcode = VALIDATE_FINANCE_IBAN_TOO_SHORT;
        } elseif (strlen($iban) > $_iban_countrycode_ibanlength[$abbrev]) {
            $errorcode = VALIDATE_FINANCE_IBAN_TOO_LONG;
        } elseif (!preg_match($_iban_countrycode_regex[$abbrev], $iban)) {
            $errorcode = VALIDATE_FINANCE_IBAN_INVALID_FORMAT;
        } else {
            // todo: maybe implement direct checks for bankcodes of certain countries

            // let's see if checksum is also correct
            $iban_replace_chars = range('A', 'Z');
            foreach (range(10, 35) as $tempvalue) {
                $iban_replace_values[] = strval($tempvalue);
            }

            // move first 4 chars (countrycode and checksum) to the end of the string
            $tempiban = substr($iban, 4) . substr($iban, 0, 4);
            $tempiban = str_replace(
                $iban_replace_chars,
                $iban_replace_values,
                $tempiban
            );

            $tempcheckvalue = intval(substr($tempiban, 0, 1));
            for ($strcounter = 1; $strcounter < strlen($tempiban); $strcounter++) {
                $tempcheckvalue *= 10;
                $tempcheckvalue += intval(substr($tempiban, $strcounter, 1));
                $tempcheckvalue %= 97;
            }

            // checkvalue of 1 indicates correct IBAN checksum
            if ($tempcheckvalue != 1) {
                $errorcode = VALIDATE_FINANCE_IBAN_CHECKSUM_INVALID;
            } else {
                $errorcode = VALIDATE_FINANCE_IBAN_OK;
            }
        }

        if (isset($this)) {
            $this->_errorcode = $errorcode;
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
            return substr($this->_iban, 0, 2);
        } else {
            $this->_errorcode = VALIDATE_FINANCE_IBAN_TOO_SHORT;
            $msg              = $this->errorMessage($this->_errorcode);
            return PEAR::raiseError(
                $msg,
                $this->_errorcode,
                PEAR_ERROR_TRIGGER,
                E_USER_WARNING,
                "$msg in VALIDATE_FINANCE_IBAN::getCountrycode()"
            );
        }
    } // end func getCountrycode

    /**
     * Returns the countryname of the IBAN
     * If the name can not be determined then return the country code.
     *
     * @access    public
     * @return    string
     */
    function getCountryname()
    {
        $countrycode = $this->getCountrycode();
        if (is_string($countrycode)) {
            $countryname = Validate_Finance_IBAN::_getCountrycodeCountryname();
            return $countryname[$countrycode];
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
            $msg              = $this->errorMessage($this->_errorcode);
            return PEAR::raiseError(
                $msg,
                $this->_errorcode,
                PEAR_ERROR_TRIGGER,
                E_USER_WARNING,
                "$msg in VALIDATE_FINANCE_IBAN::getBankcode()"
            );
        } else {
            $bankcode = Validate_Finance_IBAN::_getCountrycodeBankcode();
            $position = substr($this->_iban, 0, 2);
            // find out where the bankcode is inside the iban
            $currCountrycodeBankcode = $bankcode[$position];
            // extract and return that code
            return substr(
                $this->_iban,
                $currCountrycodeBankcode['start'],
                $currCountrycodeBankcode['length']
            );
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
            //get error message
            $msg = $this->errorMessage($this->_errorcode);
            return PEAR::raiseError(
                $msg,
                $this->_errorcode,
                PEAR_ERROR_TRIGGER,
                E_USER_WARNING,
                "$msg in VALIDATE_FINANCE_IBAN::getBankaccount()"
            );
        } else {
            $bankaccount = Validate_Finance_IBAN::_getCountrycodeBankaccount();
            //extract details
            $currCountrycodeBankaccount = $bankaccount[substr($this->_iban, 0, 2)];
            return substr(
                $this->_iban,
                $currCountrycodeBankaccount['start'],
                $currCountrycodeBankaccount['length']
            );
        }
    } // end func getAccount

    /**
     * Return a textual error message for an IBAN error code
     *
     * @param int $value error code
     *
     * @access  public
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
        if (PEAR::isError($value)) {
            $value = $value->getCode();
        }

        // return the textual error message corresponding to the code
        return isset($errorMessages[$value]) ? $errorMessages[$value] : $errorMessages[VALIDATE_FINANCE_IBAN_ERROR];
    } // end func errorMessage
} // end class VALIDATE_FINANCE_IBAN
