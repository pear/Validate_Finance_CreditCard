<?php
/**
 * Specific validation methods for data used in Italy
 * 
 * PHP version 4
 * 
 * This source file is subject to the New BSD license, That is bundled
 * with this package in the file LICENSE, and is available through
 * the world-wide-web at http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the new BSDlicense and are unable
 * to obtain it through the world-wide-web, please send a note to
 * pajoye@php.net so we can mail you a copy immediately.
 *
 * @category  Validate
 * @package   Validate_IT
 * @author    Jacopo Andrea Nuzzi <jacopo.nuzzi@gmail.com>
 * @copyright 2008 Jacopo Andrea Nuzzi
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_IT
 */

/**
 * Data validation class for Italy
 *
 * This class provides methods to validate:
 *  o Postal code
 *    (The italian CAP)
 *  o Telephone number (and prefix)
 *  o Mobile number (and prefix)
 *  o Regions (and regions code)
 *  o Cities (and cities code)
 *  o Fiscal code
 *  o Value Added Tax Identification Number (VATIN)
 *    (The italian Partita IVA)
 *  o Driver license
 *
 * @category  Validate
 * @package   Validate_IT
 * @author    Jacopo Andrea Nuzzi <jacopo.nuzzi@gmail.com>
 * @copyright 2008 Jacopo Andrea Nuzzi
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD
 * @version   Release: 0.2.0alpha
 * @link      http://pear.php.net/package/Validate_IT
 */
class Validate_IT
{
    /**
     * Validate postal code
     * (CAP - Codice d'Avviamento Postale)
     *
     * @param string $postalCode Postal code
     * 
     * @static
     * @access public
     * @return bool    true    if postal code is ok
     * @return bool    false   if postal code isn't correct
     */
    function postalCode($postalCode)
    {
        $postalCode = trim($postalCode);
        $postalCode = str_replace(' ', '', $postalCode);
        
        // Postal code has to be a number and has to be 5 characters long
        return (bool)preg_match('/^[0-9]{5}$/', $postalCode);
    }
    
    /**
     * Validate phone number
     *
     * @param string $number Phone number
     * 
     * @static
     * @access public
     * @return bool    true    if phone number is ok
     * @return bool    false   if phone number isn't correct
     */
    function phoneNumber($number)
    {
        // Erase spaces (and other symbols) and the state prefix "39"
        $number = trim($number);
        
        // Erase chars contained in this array
        $rchars = array(' ', '-', '/', '\\', '+39', '+0039');
        $number = str_replace($rchars, '', $number);
        
        // If the prefix is not correct return false
        if (Validate_IT::phoneNumberPrefix($number) === false) {
            return false;
        }
        
        // Phone number has to be a number and has to be 8 to 10 characters long
        return (bool)preg_match('/^[0-9]{8,10}$/', $number);
    }
    
    /**
     * Validate phone number prefix
     *
     * @param string $number Phone number prefix
     * 
     * @static
     * @access public
     * @return bool    true    if prefix is ok
     * @return bool    false   if prefix isn't correct
     */
    function phoneNumberPrefix($number)
    {
        // Erase spaces (and other symbols) and the state prefix "39"
        $number = trim($number);
        
        // Erase chars contained in this array
        $rchars = array(' ', '-', '/', '\\', '+39', '+0039');
        $number = str_replace($rchars, '', $number);
        
        // Italian prefixes are not all equally long.
        // They could be of 2, 3, 4 and 6 charachters
        $prefix2 = str_repeat('0', 4) . substr($number, 0, 2); // 2 charachters
        $prefix3 = str_repeat('0', 3) . substr($number, 0, 3); // 3 charachters
        $prefix4 = str_repeat('0', 2) . substr($number, 0, 4); // 4 charachters
        $prefix6 = substr($number, 0, 5);                      // 6 charachters
        
        // Prefixes array
        $prefixes = array(
            '004191', '000010', '000011', '000121', '000122', '000123', '000124', 
            '000125', '000131', '000141', '000142', '000143', '000144', '000015', 
            '000161', '000163', '000165', '000166', '000171', '000172', '000173', 
            '000174', '000175', '000182', '000183', '000184', '000185', '000187', 
            '000019', '000002', '000030', '000031', '000321', '000322', '000323', 
            '000324', '000331', '000332', '000341', '000342', '000343', '000344', 
            '000345', '000346', '000035', '000362', '000363', '000364', '000365', 
            '000371', '000372', '000373', '000374', '000375', '000376', '000377', 
            '000381', '000382', '000383', '000384', '000385', '000386', '000039', 
            '000040', '000041', '000421', '000422', '000423', '000424', '000425', 
            '000426', '000427', '000428', '000429', '000431', '000432', '000433', 
            '000434', '000435', '000436', '000437', '000438', '000439', '000442', 
            '000444', '000445', '000045', '000461', '000462', '000463', '000464', 
            '000465', '000471', '000472', '000473', '000474', '000481', '000049', 
            '000050', '000051', '000521', '000522', '000523', '000524', '000525', 
            '000532', '000533', '000534', '000535', '000536', '000541', '000542', 
            '000543', '000544', '000545', '000546', '000547', '000055', '000564', 
            '000565', '000566', '000571', '000572', '000573', '000574', '000575', 
            '000577', '000578', '000583', '000584', '000585', '000586', '000587', 
            '000588', '000059', '000006', '000700', '000071', '000721', '000722', 
            '000731', '000732', '000733', '000734', '000735', '000736', '000737', 
            '000742', '000743', '000744', '000746', '000075', '000761', '000763', 
            '000765', '000766', '000771', '000773', '000774', '000775', '000776', 
            '000781', '000782', '000783', '000784', '000785', '000789', '000079', 
            '000800', '000081', '000823', '000824', '000825', '000827', '000828', 
            '000831', '000832', '000833', '000835', '000836', '000085', '000861', 
            '000862', '000863', '000864', '000865', '000871', '000872', '000873', 
            '000874', '000875', '000881', '000882', '000883', '000884', '000885', 
            '000089', '000090', '000091', '000921', '000922', '000923', '000924', 
            '000925', '000931', '000932', '000933', '000934', '000935', '000941', 
            '000942', '000095', '000961', '000962', '000963', '000964', '000965', 
            '000966', '000967', '000968', '000971', '000972', '000973', '000974', 
            '000975', '000976', '000981', '000982', '000983', '000984', '000985', 
            '000099', 
        );
        // If the prefix has been found in array returns true otherwise false
        if (in_array($prefix2, $prefixes) ||
            in_array($prefix3, $prefixes) ||
            in_array($prefix4, $prefixes) ||
            in_array($prefix6, $prefixes)
        ) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validate mobile phone number
     *
     * @param string $number Mobile phone number
     * 
     * @static
     * @access public
     * @return bool    true    if phone number is ok
     * @return bool    false   if phone number isn't correct
     */
    function mobilePhoneNumber($number)
    {
        // Erase spaces (and other symbols) and the state prefix "39"
        $number = trim($number);
        
        // Erase chars contained in this array
        $rchars = array(' ', '-', '/', '\\', '+39', '+0039');
        $number = str_replace($rchars, '', $number);
        
        // If the prefix is not correct return false
        if (Validate_IT::mobilePhoneNumberPrefix($number) === false) {
            return false;
        }
        
        // Mobile phone number has to be a number and has to be 10 characters long
        return (bool)preg_match('/^[0-9]{10}$/', $number);
    }
    
    /**
     * Validate mobile phone number prefix
     *
     * @param string $number Mobile phone number prefix
     * 
     * @static
     * @access public
     * @return bool    true    if prefix is ok
     * @return bool    false   if prefix isn't correct
     */
    function mobilePhoneNumberPrefix($number)
    {
        // Erase spaces (and other symbols) and the state prefix "39"
        $number = trim($number);
        
        // Erase chars contained in this array
        $rchars = array(' ', '-', '/', '\\', '+39', '+0039');
        $number = str_replace($rchars, '', $number);
        
        // Get the prefix that has to be 3 charachters long
        $prefix = substr($number, 0, 3);
        
        // Prefixes array
        //
        // 320 to 329, 380 to 389    WIND
        // 330 to 339, 360 to 368    TIM
        // 340 to 349                VODAFONE
        // 390 to 393                H3G
        $prefixes = array(
            '320', '323', '327', '328', '329', '330', 
            '333', '334', '335', '336', '337', '338', 
            '339', '340', '343', '346', '347', '348', 
            '349', '360', '363', '366', '368', '380', 
            '383', '388', '389', '390', '391', '392', 
            '393'
        );
        // If the prefix has been found in array returns true otherwise false
        if (in_array($prefix, $prefixes)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validate region code
     *
     * @param string $region Region code
     * 
     * @static
     * @access public
     * @return bool    true    if region code is ok
     * @return bool    false   if region code isn't correct
     */
    function region($region)
    {
        $region = trim($region);
        $region = strtoupper($region);
        
        // If region code isn't 3 charachters long returns false
        if (!preg_match('/^[A-Z]{3}$/', $region)) {
            return false;
        }
        
        // Regions code array
        $regionsCode = array(
            'ABR', 'BAS', 'CAL', 'CAM', 'EMR', 'FVG', 'LAZ', 'LIG', 'LOM', 'MAR',
            'MOL', 'PIE', 'PUG', 'SAR', 'SIC', 'TOS', 'TAA', 'UMB', 'VDA', 'VEN'
        );
        // If the region code has been found in array returns true otherwise false
        if (in_array($region, $regionsCode)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validate region name
     *
     * @param string $region Region name
     * 
     * @static
     * @access public
     * @return bool    true    if region is ok
     * @return bool    false   if region isn't correct
     */
    function regionName($region)
    {
        $region = trim($region);
        $region = strtoupper($region);
        
        // Regions array
        $regions = array(
            'ABRUZZO', 'BASILICATA', 'CALABRIA', 'CAMPANIA', 'EMILIA ROMAGNA',
            'FRIULI VENEZIA GIULIA', 'LAZIO', 'LIGURIA', 'LOMBARDIA', 'MARCHE',
            'MOLISE', 'PIEMONTE', 'PUGLIA', 'SARDEGNA', 'SICILIA',
            'TOSCANA', 'TRENTINO ALTO ADIGE', 'UMBRIA', 'VALLE D\'AOSTA', 'VENETO'
        );
        // If the region has been found in array returns true otherwise false
        if (in_array($region, $regions)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validate city code
     *
     * @param string $city City code
     * 
     * @static
     * @access public
     * @return bool    true    if city code is ok
     * @return bool    false   if city code isn't correct
     */
    function city($city)
    {
        $city = trim($city);
        $city = strtoupper($city);
        
        // If city code isn't 2 charachters long returns false
        if (!preg_match('/^[A-Z]{2}$/', $city)) {
            return false;
        }
        
        // Cities array
        $cities = array(
            'AG', 'AL', 'AN', 'AO', 'AR', 'AP', 'AT', 'AV', 
            'BA', 'BL', 'BN', 'BG', 'BI', 'BO', 'BZ', 'BS', 
            'BR', 'CA', 'CL', 'CB', 'CI', 'CE', 'CT', 'CZ', 
            'CH', 'CO', 'CS', 'CR', 'KR', 'CN', 'EN', 'FE', 
            'FI', 'FG', 'FC', 'FR', 'GE', 'GO', 'GR', 'IM', 
            'IS', 'SP', 'AQ', 'LT', 'LE', 'LC', 'LI', 'LO', 
            'LU', 'MC', 'MN', 'MS', 'MT', 'VS', 'ME', 'MI', 
            'MO', 'NA', 'NO', 'NU', 'OG', 'OT', 'OR', 'PD', 
            'PA', 'PR', 'PV', 'PG', 'PU', 'PE', 'PC', 'PI', 
            'PT', 'PN', 'PZ', 'PO', 'RG', 'RA', 'RC', 'RE', 
            'RI', 'RN', 'RM', 'RO', 'SA', 'SS', 'SV', 'SI', 
            'SR', 'SO', 'TA', 'TE', 'TR', 'TO', 'TP', 'TN', 
            'TV', 'TS', 'UD', 'VA', 'VE', 'VB', 'VC', 'VR', 
            'VV', 'VI', 'VT'
        );
        // If the city code has been found in array returns true otherwise false
        if (in_array($city, $cities)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validate city name
     *
     * @param string $city City name
     * 
     * @static
     * @access public
     * @return bool    true    if city is ok
     * @return bool    false   if city isn't correct
     */
    function cityName($city)
    {
        $city = trim($city);
        $city = strtoupper($city);
        
        // Cities array
        $cities = array(
            'AGRIGENTO', 'ALESSANDRIA', 'ANCONA', 'AOSTA', 'AREZZO', 
            'ASCOLI PICENO', 'ASTI', 'AVELLINO', 'BARI', 'BELLUNO', 
            'BENEVENTO', 'BERGAMO', 'BIELLA', 'BOLOGNA', 'BOLZANO', 
            'BRESCIA', 'BRINDISI', 'CAGLIARI', 'CALTANISSETTA', 'CAMPOBASSO', 
            'CARBONIA-IGLESIAS', 'CASERTA', 'CATANIA', 'CATANZARO', 'CHIETI', 
            'COMO', 'COSENZA', 'CREMONA', 'CROTONE', 'CUNEO', 
            'ENNA', 'FERRARA', 'FIRENZE', 'FOGGIA', 'FORLÒ-CESENA', 
            'FROSINONE', 'GENOVA', 'GORIZIA', 'GROSSETO', 'IMPERIA', 
            'ISERNIA', 'LA SPEZIA', 'L\'AQUILA', 'LATINA', 'LECCE', 
            'LECCO', 'LIVORNO', 'LODI', 'LUCCA', 'MACERATA', 
            'MANTOVA', 'MASSA-CARRARA', 'MATERA', 'MEDIO CAMPIDANO', 'MESSINA', 
            'MILANO', 'MODENA', 'NAPOLI', 'NOVARA', 'NUORO', 
            'OGLIASTRA', 'OLBIA-TEMPIO', 'ORISTANO', 'PADOVA', 'PALERMO', 
            'PARMA', 'PAVIA', 'PERUGIA', 'PESARO E URBINO', 'PESCARA', 
            'PIACENZA', 'PISA', 'PISTOIA', 'PORDENONE', 'POTENZA', 
            'PRATO', 'RAGUSA', 'RAVENNA', 'REGGIO CALABRIA', 'REGGIO EMILIA', 
            'RIETI', 'RIMINI', 'ROMA', 'ROVIGO', 'SALERNO', 
            'SASSARI', 'SAVONA', 'SIENA', 'SIRACUSA', 'SONDRIO', 
            'TARANTO', 'TERAMO', 'TERNI', 'TORINO', 'TRAPANI', 
            'TRENTO', 'TREVISO', 'TRIESTE', 'UDINE', 'VARESE', 
            'VENEZIA', 'VERBANO-CUSIO-OSSOLA', 'VERCELLI', 'VERONA', 
            'VIBO VALENTIA', 'VICENZA', 'VITERBO', 
        );
        // If the city has been found in array returns true otherwise false
        if (in_array($city, $cities)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validate fiscal code
     *
     * @param string $code Fiscal code
     * 
     * @static
     * @access public
     * @return bool    true    if fiscal code is ok
     * @return bool    false   if fiscal code isn't correct
     * @link http://it.wikipedia.org/wiki/Codice_fiscale
     */
    function fiscalCode($code)
    {
        $code = str_replace(' ', '', strtoupper(trim($code)));
        
        $numbers1 = 0;
        $numbers2 = 0;
        
        // Array of values in not odd position
        $numbers1Array = array('0' => '0', '1' => '1', '2' => '2', '3' => '3',
                               '4' => '4', '5' => '5', '6' => '6', '7' => '7',
                               '8' => '8', '9' => '9', 'A' => '0', 'B' => '1',
                               'C' => '2', 'D' => '3', 'E' => '4', 'F' => '5',
                               'G' => '6', 'H' => '7', 'I' => '8', 'J' => '9',
                               'K' => '10', 'L' => '11', 'M' => '12', 'N' => '13',
                               'O' => '14', 'P' => '15', 'Q' => '16', 'R' => '17',
                               'S' => '18', 'T' => '19', 'U' => '20', 'V' => '21',
                               'W' => '22', 'X' => '23', 'Y' => '24', 'Z' => '25');
        
        // Array of values in odd position
        $numbers2Array = array('0' => '1', '1' => '0', '2' => '5', '3' => '7',
                               '4' => '9', '5' => '13', '6' => '15', '7' => '17',
                               '8' => '19', '9' => '21', 'A' => '1', 'B' => '0',
                               'C' => '5', 'D' => '7', 'E' => '9', 'F' => '13',
                               'G' => '15', 'H' => '17', 'I' => '19', 'J' => '21',
                               'K' => '2', 'L' => '4', 'M' => '18', 'N' => '20',
                               'O' => '11', 'P' => '3', 'Q' => '6', 'R' => '8',
                               'S' => '12', 'T' => '14', 'U' => '16', 'V' => '10',
                               'W' => '22', 'X' => '25', 'Y' => '24', 'Z' => '23');
        
        // Division result array
        $resultArray = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', 
                               '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', 
                               '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', 
                               '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', 
                               '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', 
                               '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', 
                               '24' => 'Y', '25' => 'Z');
        
        for ($i = 0; $i < 15; $i++) {
            if (($i + 1) % 2 == 0) {
                $numbers1 += $numbers1Array[$code{$i}];
            } else {
                $numbers2 += $numbers2Array[$code{$i}];
            }
        }
        // Sum odd and not odd numbers and take the rest of the division
        $controlLetter = $resultArray[(($numbers1 + $numbers2) % 26)];
        
        // Italian fiscal code identify a person and it contains several details
        // like first and second name, bitrh date and place.
        //
        // It has to be like this: XXX XXX 11X11 X111X without spaces.
        // If it's not like that returns false
        return (bool)preg_match('/' .
            '([A-Z]+){6}' .
            '([0-9]+){2}([A|B|C|D|E|H|L|M|P|R|S|T]+){1}([0-9]+){2}' .
            '([A-Z]+){1}([0-9]+){3}([' . $controlLetter . ']+){1}' .
            '/', $code);
    }
    
    /**
     * Validate Value Added Tax Identification Number (VATIN)
     * Italian "Partita IVA"
     *
     * @param string $iva P.IVA
     * 
     * @static
     * @access public
     * @return bool    true    if P.IVA is ok
     * @return bool    false   if P.IVA isn't correct
     * @link http://en.wikipedia.org/wiki/Value_added_tax_identification_number
     * @link http://it.wikipedia.org/wiki/Partita_IVA
     */
    function pIva($iva)
    {
        $iva = str_replace(' ', '', trim($iva));
        
        // If the P.IVA isn't 11 charachters long and isn't a number returns false
        if (!preg_match('!^([0-9]+){11}$!', $iva)) {
            return false;
        }
        
        // Get the length of the the code ($iva)
        $length   = strlen($iva);
        $numbers1 = 0;
        $numbers2 = 0;
        for ($i = 0; $i <= $length; $i++) {
            if (($i + 1) % 2 == 0) {
                // Multiply not odd numbers
                $num = ($iva{$i} * 2);
                // If number is greater than 9 then here we have to subtract 9
                // to the number
                if ($num > 9) {
                    $num -= 9;
                }
                
                // Sum not odd numbers
                $numbers1 += $num;
            } else {
                // Sum odd numbers
                $numbers2 += $iva{$i};
            }
        }
        
        // Sum the two numbers and divide to 10
        // If the result is zero rest then the P.IVA is correct
        $op = (($numbers1 + $numbers2) / 10);
        if (!preg_match('!\.!', $op)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validate the driver license
     *
     * @param string $license Driver license
     * 
     * @static
     * @access public
     * @return bool    true    if driver license is ok
     * @return bool    false   if driver license isn't correct
     */
    function driverLicense($license) {
        $license = strtoupper(trim($license));
        
        return (bool)preg_match('/^([A-Z]+){2}([0-9]+){7}([A-Z]+){1}$/', $license);
    }
}
?>