<?php
    
/**
 * Specific validation for data pertaining to the Republic of India.
 *
 * This class will validate Indian:
 *  - Postal Codes (Zip Codes)
 *  - State/U.T. Codes
 *  - Telephone Numbers
 *  - PAN/TAN Numbers
 *  - Vehicle license plate Numbers
 *
 * @category   Validate
 * @package    Validate_IN
 * @author     Anant Narayanan <anant@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 */

class Validate_IN
{
    // {{{ pan()
    
    /**
     * Validates an Indian Permanent Account Number (PAN) or
     * Tax deduction and collection Account Number (TAN).
     *
     * @param   string  $number     The PAN or TAN to be validated.
     *
     * @return  boolean TRUE if code is valid, FALSE otherwise
     * 
     * @access  public
     * @static
     */
    function pan($number)
    {
        return (bool)preg_match('/^[A-Z]{3}[A-Z0-9]{7}$/', $number);
    }

    //}}}
    //{{{ postalCode()
    
    /**
     * Validates an Indian Postal Code (ZIP code)
     *
     * @param   string  $postalCode The ZIP code to validate
     *
     * @return  boolean TRUE if code is valid, FALSE otherwise
     *
     * @access  public
     * @static
     */
    function postalCode($postalCode)
    {
        return (bool)preg_match('/^[1-9]{1}[0-9]{2}(\s|\-)?[0-9]{3}$/', $postalCode);
    }

    //}}}
    //{{{ stateCode()

    /**
     * Validates a state / union territory code and returns the full name of the
     * state / union territory code passed.
     *
     * @param   string  $region     2-letter state / union territory code
     *
     * @return  bool    True if state code is valid, False otherwise.  
     * 
     * @access  public
     * @static
     */
    function stateCode($stateCode)
    {
        switch (strtoupper($stateCode)) {
            case 'AN':  
            case 'AP': 
            case 'AR': 
            case 'AS': 
            case 'BR':  
            case 'CH': 
            case 'CT':  
            case 'DN': 
            case 'DD': 
            case 'DL':  
            case 'GA':  
            case 'GJ':  
            case 'HR':  
            case 'HP':  
            case 'JK': 
            case 'JH':  
            case 'KA':  
            case 'KL':  
            case 'LD':  
            case 'MP': 
            case 'MH': 
            case 'MN': 
            case 'ML':  
            case 'MZ':  
            case 'NL':  
            case 'OR': 
            case 'PY':  
            case 'PB': 
            case 'RJ':  
            case 'SK': 
            case 'TN': 
            case 'TR':  
            case 'UL': 
            case 'UP':  
            case 'WB':  
                return true;
        }
        return false;
    }

    //}}}
    //{{{ getStateName()

    /**
     * Returns the full name of a state / union territory given a valid state
     * code. If state code is invalid or NULL, an array of all states is
     * returned.
     *
     * @param   String  $code   2 letter State / U.T. Code.
     *
     * @return  String/Array    Full name of state of code is valid, array of
     *                          all states if not.
     *
     * @access  public
     * @static
     */
    function getStateName($code = NULL)
    {
        $states = array("Andaman and Nicobar Islands",
                        "Andhra Pradesh",
                        "Arunachal Pradesh",
                        "Assam",
                        "Bihar",
                        "Chandigarh",
                        "Chattisgarh",
                        "Dadra and Nagar Haveli",
                        "Daman and Diu",
                        "Delhi",
                        "Goa",
                        "Gujarat",
                        "Harayana",
                        "Himachal Pradesh",
                        "Jammu and Kashmir",
                        "Jharkhand",
                        "Karnataka",
                        "Kerala",
                        "Lakshwadeep",
                        "Madhya Pradesh",
                        "Maharashtra",
                        "Manipur",
                        "Meghalaya",
                        "Mizoram",
                        "Nagaland",
                        "Orissa",
                        "Pondicherry",
                        "Punjab",
                        "Rajasthan",
                        "Sikkim",
                        "Tamil Nadu",
                        "Tripura",
                        "Uttaranchal",
                        "Uttar Pradesh",
                        "West Bengal");

        switch (strtoupper($code)) {
            case 'AN':  return $states[0];
                        break;
            case 'AP':  return $states[1];
                        break;
            case 'AR':  return $states[2];
                        break;
            case 'AS':  return $states[3];
                        break;
            case 'BR':  return $states[4];
                        break;
            case 'CH':  return $states[5];
                        break;
            case 'CT':  return $states[6];
                        break;
            case 'DN':  return $states[7];
                        break;
            case 'DD':  return $states[8];
                        break;
            case 'DL':  return $states[9];
                        break;
            case 'GA':  return $states[10];
                        break;
            case 'GJ':  return $states[11];
                        break;
            case 'HR':  return $states[12];
                        break;
            case 'HP':  return $states[13];
                        break;
            case 'JK':  return $states[14];
                        break;
            case 'JH':  return $states[15];
                        break;
            case 'KA':  return $states[16];
                        break;
            case 'KL':  return $states[17];
                        break;
            case 'LD':  return $states[18];
                        break;
            case 'MP':  return $states[19];
                        break;
            case 'MH':  return $states[20];
                        break;
            case 'MN':  return $states[21];
                        break;
            case 'ML':  return $states[22];
                        break;
            case 'MZ':  return $states[23];
                        break;
            case 'NL':  return $states[24];
                        break;
            case 'OR':  return $states[25];
                        break;
            case 'PY':  return $states[26];
                        break;
            case 'PB':  return $states[27];
                        break;
            case 'RJ':  return $states[28];
                        break;
            case 'SK':  return $states[29];
                        break;
            case 'TN':  return $states[30];
                        break;
            case 'TR':  return $states[31];
                        break;
            case 'UL':  return $states[32];
                        break;
            case 'UP':  return $states[33];
                        break;
            case 'WB':  return $states[34];
                        break;
            default:    return $states;
                        break;
        }
    }

    //}}}
    //{{{ phoneNumber()

    /**
     * Validate an Indian Phone number.
     *
     * Allows the following formats:
     *
     *  (xxx) xxxxxxx
     *  xxx xxxxxxx
     *  +91 xxx xxxxxxx
     *  091xxxxxxxxxx
     *
     * where whitespaces, dashes and brackets may interchanged freely and 0/+ may
     * be added / skipped wherever possible.
     *
     * @param   string  $number         Phone number to validate (mobile or
     *                                  landline)
     *
     * @return  bool    True if number is valid, False otherwise
     *
     * @access  public
     * @static
     */
    function phoneNumber($number)
    {
        // strip country code
        if (substr($number, 0, 3)=='091' or substr($number, 0, 3)=='+91') {
            $number = substr($number, 3, strlen($number)-3);
        }
        // no numbers in India begin with 91, so safely strip country code
        if (substr($number, 0, 2)=='91') {
            $number = substr($number, 2, strlen($number)-2);
        }

        // it's a mobile number
        if (preg_match('/^9(2|3|4|8|9)(\s)?(\-)?(\s)?[1-9]{1}[0-9]{7}$/', $number)) {
            return true;
        }

        // it's a landline, with or without area code
        if (preg_match('/^\(?(0[1-9]{2,5}|\d{2,5})?(\s)?(\s|\)|\-)?(\s)?(2|3|5)\d{6,7}$/',
            $number)) {          
            return true;
        }
       
        return false;
    }

    //}}}
    //{{{ licensePlate()

    /**
     * Validates an Indian Vehicle's license plate number.
     *
     * @param   string  $license    The license plate number to validate.
     *
     * @return  boolean TRUE if code is valid, FALSE otherwise
     *
     * @access  public
     * @static
     */
    function licensePlate($number)
    {
        if (Validate_IN::stateCode(substr($number, 0, 2))) {
            // state code is valid
            return (bool)
            preg_match('/^[A-Z]{2}(\s|\-)?[0-9]{1,2}(\s|\-)?(S|C|R|V)?(\s|\-)?[A-Z]{0,2}(\s|\-)?\d{4}$/',
            $number);
        }

        return false;
    }
    
    //}}}
}

/* END */

?>
