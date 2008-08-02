<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2008 Tibor balogh, Zoltan Koteles                      |
// +----------------------------------------------------------------------+
// | Redistribution and use in source and binary forms, with or without   |
// | modification, are permitted provided that the following conditions   |
// | are met:                                                             |
// |                                                                      |
// | Redistributions of source code must retain the above copyright       |
// | notice, this list of conditions and the following disclaimer.        |
// |                                                                      |
// | Redistributions in binary form must reproduce the above copyright    |
// | notice, this list of conditions and the following disclaimer in the  |
// | documentation and/or other materials provided with the distribution. |
// |                                                                      |
// | Neither the name of the Tibor Balogh, Zoltan Koteles nor the names   |
// | of its contributors may be used to endorse or promote products       |
// | derived from this software without specific prior written permission.|
// |                                                                      |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT    |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR|
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,|
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT     |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,|
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY|
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT  |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE|
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. |
// +----------------------------------------------------------------------+
// | Author: Tibor Balogh <btibor@dolphinet.hu>                           |
// |         Zoltan Koteles <koteleszoltan@dolphinet.hu>                  |
// +----------------------------------------------------------------------+ 

/**
 * Specific validation methods for data used in Hungary
 *
 * @category  Validate
 * @package   Validate_HU
 * @author    Tibor Balogh <btibor@dolphinet.hu>
 * @author    Zoltan Koteles <koteleszoltan@dolphinet.hu>
 * @copyright 2008 Tibor Balogh, Zoltan Koteles
 * @license   http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @link      http://www.dolphinet.hu
 */

/**
 * Data validation class for Hungary
 *
 * This class provides methods to validate:
 *  - Postal code (H-7090, 7090)
 *  - Identity card (123456AA, AA123456)
 *  - Tax number
 *  - Bank account number
 *  - SSN number (TAJ szam)
 *
 * @category Validate
 * @package  Validate_HU
 * @author   Tibor Balogh <btibor@dolphinet.hu>
 * @author   Zoltan Koteles <koteleszoltan@dolphinet.hu>
 * @license  http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version  Release: @package_version@
 * @link     http://www.dolphinet.hu
 */
class Validate_HU
{
    /**
     * Validate a HU postcode
     *
     * @param string $postcode HU postcode to validate
     *
     * @return bool  true if postcode is ok false otherwise
     */
    function postalCode($postcode)
    {
        return (bool)preg_match('/^(H-)?\d{4}$/', $postcode);
    }

    /**
     * Identity card validation check
     *
     * @param string $number Hungarian identity card
     *
     * @return bool true if identity card 'seems' correct
     */
    function identityCard($number)
    {
        return (bool)preg_match('/^[A-Z]{2}\d{6}$|^\d{6}[A-Z]{2}$/i', $number);
    }

    /**
     * Validates CDV of a number
     * 
     * @param string $number number to check
     *
     * @access protected
     *
     * @return bool
     */
    function _checkCDV($number) 
    {
        $cdv     = intval(substr($number, -1));
        $digits  = strlen($number)-1;
        $weights = array(9, 7, 3, 1);
        $sum     = 0;
        for ($i = 0; $i < $digits; $i++) {
            $sum += $weights[$i % 4] * intval(substr($number, $i, 1));
        }
        return (((10 - ($sum % 10)) % 10) == $cdv); 
    }

    /**
     * Tax number validation check
     *
     * @param mixed $number Hungarian taxnumber
     * @param mixed $dashes uses dashes in number or not
     *
     * @access public
     * @return void
     */
    function taxNumber($number, $dashes = false)
    {
        $dash = $dashes ? "-" : "";
        if (!preg_match("/^\d{8}".$dash."\d".$dash."\d{2}$/", $number)) {
            return false;
        }
        return Validate_HU::_checkCDV(substr($number, 0, 8));
    }

    /**
     * Bank account validation check 
     *
     * @param string $number Hungarian bankaccount number
     * @param bool   $dashes Number contains 1 or 2 dashes, default is false
     *
     * @return bool true if bankaccount number 'seems' correct
     */

    function bankAccountNumber($number, $dashes = false)
    {
        $dash = $dashes ? "-" : "";
        if (!preg_match("/^\d{8}".$dash."\d{8}(".$dash."\d{8})?$/", $number)) {
            return false;
        }
        $number = preg_replace("/-|0{8}$/", "", $number);
        $len    = strlen($number);

        return 
            Validate_HU::_checkCDV(substr($number, 0, 8)) &&
            (($len == 16 && Validate_HU::_checkCDV(substr($number, 8, 8))) ||
            ($len == 24 && Validate_HU::_checkCDV(substr($number, 8, 16))));
    }

    /**
     * Validate SSN (TAJ szam)
     *
     * @param string $ssn ssn number
     *
     * @access public
     * @return bool
     */
    function ssn($ssn)
    {
        if (!preg_match("/^\d{9}$/", $ssn)) return false;

        $weights = array(3, 7);
        $sum     = 0;
        for ($i = 0; $i<8; $i++) {
            $sum += $weights[$i % 2] * intval(substr($ssn, $i, 1));
        }
        return substr($ssn, 8, 1) == ($sum % 10);
    }
}
?>
