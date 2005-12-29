<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005  Silvano Girardi Jr.                         |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Silvano Girardi Jr. <silvano@php.net>                        |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in Brazil
 *
 * @category   Validate
 * @package    Validate_ptBR
 * @author     Silvano Girardi Jr. <silvano@php.net>
 * @author     Marcelo Santos Araujo <msaraujo@php.net>
 * @copyright  1997-2005  Silvano Girardi Jr.
 * @license    http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_ptBR
 */

/**
 * Data validation class for Brazil
 *
 * This class provides methods to validate:
 *  - Postal code CEP (Código de Endereçamento Postal)
 *  - CPF (Cadastro de Pessoa Física)
 *  - CNPJ (Cadastro Nacional de Pessoa Jurídica)
 *  - Regions - brazilian states (Estados brasileiros)
 *  - Phone Numbers - brazilian phone numbers
 *  - Vehicle Plates - brazilian vehicle's plate
 * 
 * @category   Validate
 * @package    Validate_ptBR
 * @author     Silvano Girardi Jr. <silvano@php.net>
 * @author     Marcelo Santos Araujo <msaraujo@php.net>
 * @copyright  1997-2005  Silvano Girardi Jr.
 * @license    http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_ptBR
 */
class Validate_ptBR
{
    /**
     * Validate CEP (Código de Endereçamento Postal, like postcode in US
     * and other languages)
     * format: xxxxx-xxx,xxxxx xxx,xxxxxxxx
     *
     * @param   string  $postalCode   pt_BR CEP/postalCode to validate
     * @param    bool    optional; strong checks (e.g. against a list of postcodes) (not implanted)
     * @return  bool           true if $cep is ok, false otherwise
     */
    function postalCode($postalCode, $strong = false)
    {
        return (bool)ereg('(^[0-9]{5})[- ]?([0-9]{3})$', $postalCode);
    }

    /**
     * Validade CPF (Cadastro de Pessoa Física)
     *
     * @param   string $cpf  CPF to validate
     * @return  bool         true if $cpf is ok, false otherwise
     */
    function cpf($cpf)
    {
        $cleaned = '';
        for ($i = 0; $i < strlen($cpf); $i++) {
            $num = substr($cpf, $i, 1);
            if (ord($num) >= 48 && ord($num) <= 57) {
                $cleaned .= $num;
            }
        }
        $cpf = $cleaned;

        if (strlen($cpf) != 11) {
            return false;
        } elseif ($cpf == '00000000000') {
            return false;
        } else {
            $number[0]  = intval(substr($cpf, 0, 1));
            $number[1]  = intval(substr($cpf, 1, 1));
            $number[2]  = intval(substr($cpf, 2, 1));
            $number[3]  = intval(substr($cpf, 3, 1));
            $number[4]  = intval(substr($cpf, 4, 1));
            $number[5]  = intval(substr($cpf, 5, 1));
            $number[6]  = intval(substr($cpf, 6, 1));
            $number[7]  = intval(substr($cpf, 7, 1));
            $number[8]  = intval(substr($cpf, 8, 1));
            $number[9]  = intval(substr($cpf, 9, 1));
            $number[10] = intval(substr($cpf, 10, 1));

            $sum = 10*$number[0]+9*$number[1]+8*$number[2]+7*$number[3]+
                6*$number[4]+5*$number[5]+4*$number[6]+3*$number[7]+
                2*$number[8];

            $sum -= (11*(intval($sum/11)));

            if ($sum == 0 || $sum == 1) {
                $result1 = 0;
            } else {
                $result1 = 11-$sum;
            }

            if ($result1 == $number[9]) {
                $sum = $number[0]*11+$number[1]*10+$number[2]*9+$number[3]*8+
                        $number[4]*7+$number[5]*6+$number[6]*5+$number[7]*4+
                        $number[8]*3+$number[9]*2;
                $sum -= (11*(intval($sum/11)));

                if ($sum == 0 || $sum == 1) {
                    $result2 = 0;
                } else {
                    $result2 = 11-$sum;
                }

                if ($result2 == $number[10]) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * Validade CNPJ (Cadastro Nacional de Pessoa Jurídica)
     *
     * @param   string $cnpj  CNPJ to validate
     * @return  bool          true if $cnpj is ok, false otherwise
     */
    function cnpj($cnpj)
    {
        $cleaned = '';
        for ($i = 0; $i < strlen($cnpj); $i++) {
            $num = substr($cnpj, $i, 1);
            if (ord($num) >= 48 && ord($num) <= 57) {
                $cleaned .= $num;
            }
        }
        $cnpj = $cleaned;
        if (strlen($cnpj) != 14) {
            return false;
        } elseif ($cnpj == '00000000000000') {
            return false;
        } else {
            $number[0]  = intval(substr($cnpj, 0, 1));
            $number[1]  = intval(substr($cnpj, 1, 1));
            $number[2]  = intval(substr($cnpj, 2, 1));
            $number[3]  = intval(substr($cnpj, 3, 1));
            $number[4]  = intval(substr($cnpj, 4, 1));
            $number[5]  = intval(substr($cnpj, 5, 1));
            $number[6]  = intval(substr($cnpj, 6, 1));
            $number[7]  = intval(substr($cnpj, 7, 1));
            $number[8]  = intval(substr($cnpj, 8, 1));
            $number[9]  = intval(substr($cnpj, 9, 1));
            $number[10] = intval(substr($cnpj, 10, 1));
            $number[11] = intval(substr($cnpj, 11, 1));
            $number[12] = intval(substr($cnpj, 12, 1));
            $number[13] = intval(substr($cnpj, 13, 1));

            $sum = $number[0]*5+$number[1]*4+$number[2]*3+$number[3]*2+
                   $number[4]*9+$number[5]*8+$number[6]*7+$number[7]*6+
                   $number[8]*5+$number[9]*4+$number[10]*3+$number[11]*2;

            $sum -= (11*(intval($sum/11)));

            if ($sum == 0 || $sum == 1) {
                $result1 = 0;
            } else {
                $result1 = 11-$sum;
            }

            if ($result1 == $number[12]) {
                $sum = $number[0]*6+$number[1]*5+$number[2]*4+$number[3]*3+
                        $number[4]*2+$number[5]*9+$number[6]*8+$number[7]*7+
                        $number[8]*6+$number[9]*5+$number[10]*4+$number[11]*3+
                        $number[12]*2;
                $sum -= (11*(intval($sum/11)));
                if ($sum == 0 || $sum == 1) {
                    $result2 = 0;
                } else {
                    $result2 = 11-$sum;
                }

                if ($result2 == $number[13]) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    
    /**
     * Validates a "region" (i.e. state) code
     *
     * @param string $region 2-letter state code
     * @return bool  true if $region is ok, false otherwise
     * @static
     */
    function region($region)
    {
        switch (strtoupper($region)) {
            case 'AC':
            case 'AP':
            case 'AL':
            case 'AM':
            case 'BA':
            case 'CE':
            case 'DF':
            case 'ES':
            case 'GO':
            case 'MA':
            case 'MT':
            case 'MS':
            case 'MG':
            case 'PA':
            case 'PB':
            case 'PR':
            case 'PE':
            case 'PI':
            case 'RJ':
            case 'RN':
            case 'RS':
            case 'RO':
            case 'RR':
            case 'SC':
            case 'SP':
            case 'SE':
            case 'TO':
                return true;
        }
        return false;
    }


    /**
     * Validates a brazilian (ptBR) phone number.
     * Also allows the formats
     * If $requiredAreaCode is true:
     * (XX)-XXXX-XXXX,(XX) XXXX XXXX, (XX)-XXXX XXXX, (XX) XXXX-XXXX, 
     * XX-XXXX-XXXX,XX XXXX XXXX,XX-XXXX XXXX,XX XXXX-XXXX,XX XXXXXXXX,(XX)XXXXXXXX 
     * If $requiredAreaCode is false:  XXXX-XXXX,XXXX XXXX, XXXXXXXX
     * @param  string    $number             phone to validate
     * @param  bool      $requireAreaCode    require the area code? (default: true)
     * @return bool                          The valid or invalid phone number
     */
    function phoneNumber($number, $requireAreaCode = true)
    {
        if (!$requireAreaCode) {
            if (preg_match("/^(\d{4})[- ]?(\d{4})$/", $number)) {
                return  true;
            }
        } else {
            if (preg_match("/^(\()?[1-9]{2}(?(1)\))[- ]?(\d{4})[- ]?(\d{4})$/",$number)) {
            	return true;
            }
        }
        return false;
    }

    /**
     * Validates a brazilian (ptBR) vehicle's plate
     * Also allows the following formats
     * XXX-XXXX,XXX XXXX,XXXXXXX
     * The first three chars are [A-Z]
     * The last four chars are [0-9]
     * @param string $reg vehicle's plate
     * @return bool
     */
    function carReg($reg)
    {
      return ereg('^[A-Z]{3}[- ]?[0-9]{4}$', $reg);
    }
}
?>
