<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 Pierre-Alain Joye,Tomas V.V.Cox              |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Tomas V.V.Cox  <cox@idecnet.com>                             |
// |         Pierre-Alain Joye <pajoye@php.net>                           |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in Spain
 *
 * @category   Validate
 * @package    Validate_ES
 * @author     Tomas V.V.Cox <cox@idecnet.com>
 * @copyright  1997-2005 Pierre-Alain Joye,Tomas V.V.Cox
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_ES
 */

/**
* Requires base class Validate
*/
require_once 'Validate.php';

/**
 * Data validation class for Spain
 *
 * This class provides methods to validate:
 *  - El Documento Nacional de Indentidad a chequear
 *
 * @category   Validate
 * @package    Validate_ES
 * @author     Tomas V.V.Cox <cox@idecnet.com>
 * @copyright  1997-2005 Pierre-Alain Joye,Tomas V.V.Cox
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_ES
 */
class Validate_ES
{
    /**
    * Valida un DNI Español (el dni tiene que ser de la forma 11111111X)
    *
    * @param string $dni El Documento Nacional de Indentidad a chequear
    * @return bool
    */
    function dni($dni)
    {
        $letra  = substr($dni, -1);
        $number = substr($dni, 0, -1);
        if (!Validate::string($number, VALIDATE_NUM, 8, 8)) {
            return false;
        }
        if (!Validate::string($letra, VALIDATE_ALPHA)) {
            return false;
        }
        // El resto entero de la division del numero del dni/23 +1
        // es la posicion de la letra en la cadena $string
        $string = 'TRWAGMYFPDXBNJZSQVHLCKET';
        // ver la letra de un numero
        if ($letra == $string{$number % 23}) {
            return true;
        }
        return false;
    }
}
?>