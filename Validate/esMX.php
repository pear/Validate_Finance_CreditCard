<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * Specific validation methods for data used in MX (Mexico)
 *
 * PHP versions 4
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Validate
 * @package    Validate_esMX
 * @author     Pablo Fischer <pfischer@php.net>
 * @copyright  2005 The PHP Group
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_esMX
 */

/**
 * Data validation class for Mexico
 *
 * This class provides methods to validate:
 *  - DNI - Nacional Identity document (aka CURP)
 *  - Postal code
 *
 * @category   Validate
 * @package    Validate_esMX
 * @author     Pablo Fischer <pfischer@php.net>
 * @copyright  2005 The PHP Group
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_esMX
 */
class Validate_esMX
{
    /**
     * Validates a postal code
     *
     * Validates the given postal code in two ways:
     *
     *  - Check that the postal code really exists. It uses a file where  
     *    all postal codes are.
     *  - Doing a simple regexp: postal codes should be formed of 5 numbers.
     *
     * @access  public
     * @param   int     $postalCode  Postal code to validate
     * @param   boolean $strongCheck True  = It uses a file and checks that the postal code exists (Default) 
     *                               False = It uses a regexp to do the validation.
     * @return  boolean Passed / Not passed
     */
    function postalCode($postalCode, $strongCheck = false) 
    {
        if ($strongCheck) {
            static $postalCodes;

            if (!isset($postalCodes)) {
                $file = '@DATADIR@/Validate_esMX/esMX_postcodes.txt';
                $postalCodes = file($file);
            }
            return in_array((int)$postalCode, $postalCodes);
        } 
        return (bool)ereg('^[0-9]{5}$', $postalCode);
    }

    /**
     * Validates the DNI (CURP)
     *
     * It uses the following explanation:
     *
     *  http://web2.tramitanet.gob.mx/info/curp/gifs/ayuda.gif
     *
     * @access  public
     * @param   string  $dni The CURP code
     * @return  boolean Passed / Not passed
     */
    function dni($dni)
    {
        $dns = strtoupper($dni);
        //Clean it
        $dni = str_replace(array('-', ' '), '', $dni);
        //How big is it?
        if (strlen($dni) !== 18) {
            return false;
        }
        //MISSING: Continue..
        return true;
    }

    /**
     * Validates a "region" (aka state) code
     *
     * @access  public
     * @param   string $region Region/State code
     * @return  boolean Passed / Not passed
     */
    function region($region)
    {
        switch (strtoupper($region)) {
        case 'AS': //Aguascalientes
        case 'BC': //Baja California
        case 'BS': //Baja California Sur
        case 'CC': //Campeche
        case 'CL': //Coahuila
        case 'CM': //Colima
        case 'CS': //Chiapas
        case 'CH': //Chihuahua
        case 'DF': //Distrito Federal
        case 'DG': //Durango
        case 'GT': //Guanajuato
        case 'GR': //Guerrero
        case 'HG': //Hidalgo
        case 'JC': //Jalisco
        case 'MC': //Mexico
        case 'MN': //Michoacán
        case 'MS': //Morelos
        case 'NT': //Nayarit
        case 'NL': //Nuevo León
        case 'OC': //Oaxaca
        case 'PL': //Puebla
        case 'QT': //Querétaro
        case 'QR': //Quintana Roo
        case 'SP': //San Luis Potosí
        case 'SL': //Sinaloa
        case 'SR': //Sonora
        case 'TC': //Tabasco
        case 'TS': //Tamaulipas
        case 'TL': //Tlaxcala
        case 'VZ': //Veracruz
        case 'YN': //Yucatán
        case 'ZS': //Zacatecas
            return true;
        }
        return false;
    }
}
