<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 2006 Byron Adams                                     |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Byron Adams <Byron.adams54@gmail.com>                        |
// +----------------------------------------------------------------------+
//
/**
 *  Data validation class for New Zealand
 *
 * This class provides methods to validate:
 *  - IRD numbers
 *  - Regional codes
 *  - Telephone number
 *  - Postal code
 *  - Bank AC
 *
 * @category   Validate
 * @package    Validate_NZ
 * @author     Byron Adams <Byron.adams54@gmail.com>
 * @copyright  (c) 2006 Byron Adams
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 */

class Validate_NZ
{
    /**
     * Validate  New Zealand postal codes
     *
     * @access   public
     * @param    string    $postcode, postcode to validate
     * @param    bool      $strong, optional; strong checks against a list of postcodes
     * @return   bool      The valid or invalid postal code 
     * @link     http://www.nzpost.co.nz/nzpost/images/addressing.nzpost/pdfs/postcodedirectory_nomaps.pdf
     */
    function postalCode($postcode,$strong = false)
    {	
        if (!ctype_digit($postcode) || $postcode > 0110 && $postcode < 9822) {
            return false;
        } else {
        
            if ($strong ) {
                
                $postcodes = array("0110","0420","0310","1010","0610","0600","2012","2105",
                                   "0505","1081","1022","2102","2010","2022","2013","0630",
                                   "0614","0612","2014","1025","0931","3210","3214","3204",
                                   "3200","3410","3118","3112","3015","4130","4122","4110",
                                   "4312","4501","4500","4310","4825","4820","5032","5024",
                                   "5510","4410","5036","5018","6022","5010","6011","6037",
                                   "5028","5034","7281","7173","7196","7073","7183","7005",
                                   "7007","7022","7025","7071","7072","7077","7081","7091",
                                   "7095","7096","7220","7110","7120","7282","7284","7175",
                                   "7182","7194","7192","7193","7195","7197","7198","7204",
                                   "7210","7100","7271","7272","7273","7274","7275","7276",
                                   "7285","7178","7201","7010","7011","7020","8013","8041",
                                   "7334","8011","7402","8022","8083","8062","7999","8024",
                                   "8053","8051","7832","7300","7802","8042","8025","8081",
                                   "7610","7825","8014","9810","9016","9010","9822","9400",
                                   "9012","9014","9022","9013","9023","9401","9812");

                return in_array($postcode, $postcodes);
            }
        }
        return preg_match('^[0-9]{4}$', $postcode);
    }

    /**
     * Validates a New Zealand IRD Number (ssn equiv)
     *
     * recently the format has changed to having a
     * prefix of 0, this will work with both new and old IRD numbers.
     *
     * @access  public
     * @param   string     $ssn,  IRD number to validate
     * @return  bool       The valid or invalid ird number
     */
    function ssn($ssn)
    {
        $ssn = str_replace(array("-"," ","."),'',trim($ssn));

        if (!ctype_digit($ssn)) {
            return false;
        }
        
        switch ($ssn) {
            case 8:
                return true;
            break;
            case 9:
                if ($ssn{0} == "0") {
                    return true;
                }
            break;
        }
        
        return false;
        
    }

    /**
     * Validates a New Zealand Regional Code
     *
     * @access    public
     * @param     string     $region, regional code to validate
     * @return    bool       The valid or invalid regional code
     * @link      http://www.google.com/apis/adwords/developer/adwords_api_regions.html
     */
    function region($region)
    {
       $regions = array("AUK","BOP","CAN","GIS","HKB","MBH","MWT","NSN","NTL","OTA","STL","TAS","TKI","WGN","WKO","WTC");
       return in_array(strtoupper($region),$regions);
    }
    
    /**
     * Validates a New Zealand phone number
     *
     * This function validates all New Zealand phone numbers
     * checks for landline,0800,0900,0508,021,027 and 025 numbers
     * allows for various combinations with spaces dashes and parentheses.
     *
     * @access    public
     * @param     string     $number, the number to validate
     * @param     bool       $requireAreaCode, require the area code? (default: true)
     * @return    bool       The valid or invalid phone number
     */
    function phoneNumber($number, $requireAreaCode = true)
    {
        
       $number = str_replace(array("+"," ","(",")","-"),array("00","","","",""),trim($number));
       
       if (!ctype_digit($number)) {
           return false;
       } else {
           
           $numlength = strlen($number);
           
           switch ($numlength) {
       	       case 7:
       	           if (!$requireAreaCode) {
       	               $regexp = "(^[0-9]{7}$)"; // Is land line w/o area code
       	           }
       	       break;
               case 9:
       	           $regexp = "(^0(3|4|6|7|9)[0-9]{7}$)";   // Is land line with area code
       	       break; 
       	       case 10:
       	           if (in_array(substr($number,0,4),array("0800","0900","0508"))) { 
       	               $regexp = "(^0(8|9|5)0(0|8)[0-9]{6}$)"; // Is 0800,0900 or 0508 number
       	           } elseif (in_array(substr($number,0,3),array("021","025","027"))) {
       	               $regexp = "(^02(1|5|7)[0-9]{3}[0-9]{4}$)"; //Is Mobile number
       	           }
       	       break;
       	       case 11:
       	           if (substr($number,0,3) == "640") {
       	               $regexp = "(^640(3|4|6|7|9)[0-9]{7})";   // Is land line with country code
       	           }
       	       break;
       	       case 13:
       	           if (substr($number,0,4) == "0064") {
       	               $regexp = "(^00640(3|4|6|7|9)[0-9]{7})";     // Is land line with country code and 00
       	           }
       	       break;
       	   }
       }
       
       if ($regexp) {
           return preg_match($regexp,$number);
       }

        return false;
    }


    /**
     * Validates a New Zealand Bank Account Number
     *
     * This function checks wheather the given value
     * is a valid New Zealand bank account number.
     * allows several formats.
     *
     * @access    public
     * @param     string     $bankcode, number to validate
     * @return    bool       The valid or invalid Bank Account Number
     */
    function bankCode($bankcode)
    {
        $bankcode = str_replace(array("-"," ","."),'',trim($bankcode));
        
        if (ctype_digit($bankcode) && strlen($bankcode) == 15) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validates a New Zealand Vehicle license plates
     *
     * This function checks wheather the given value
     * is a valid New Zealand Vehicle license plate
     * 
     * 6 characters for cars or trucks and
     * 5 characters for motorbikes and trailors
     *
     * @access    public
     * @param     string     $reg, string to validate
     * @return    bool       The valid or invalid license plate number
     */
    function carReg($reg)
    {
        $reg = trim($reg);
        return (ctype_alnum($reg) && in_array(strlen($reg),array("6","5")));  
    }
}
