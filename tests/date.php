<?php
require_once 'Validate.php';

require_once 'PHPUnit.php';
require_once( "Validate/NL.php" );

class Validate_date extends PHPUnit_TestCase
{
    var $dates = array(
            '121202'        => array(array('format'=>'%d%m%y'), true),
            '21202'         => array(array('format'=>'%d%m%y'), false),
            '121402'        => array(array('format'=>'%d%m%y'), false),
            '12120001'      => array(array('format'=>'%d%m%Y'), true),

            /* Ambiguous date >> false
             * They should be still valid. Maybe by changing the loop
             * 1st check for the Y (4digits), and then m (2digits)
             * if you got the idea ;)
             */
            '220001'        => array(array('format'=>'%j%n%Y'), false),
            '2299'          => array(array('format'=>'%j%n%y'), false),
            '2120001'       => array(array('format'=>'%j%m%Y'), false),
            /* End */

            '12121999'      => array(array('format'=>'%d%m%Y',
                                'min'=>array('01','01','1995')), true),
            '12121996'      => array(array('format'=>'%d%m%Y',
                                'min'=>array('01','01','1995'),
                                'max'=>array('01','01','1997')), true),
            '29022002'      => array(array('format'=>'%d%m%Y'), false),
            '12.12.1902'    => array(array('format'=>'%d.%m.%Y'), true),
            '12/12/1902'    => array(array('format'=>'%d/%m/%Y'), true),
            '12/12/1902'    => array(array('format'=>'%d/%m/%Y'), true),
            '12:12:1902'    => array(array('format'=>'%d:%m:%Y'), true),
            '12'            => array(array('format'=>'%g'), true),
            '12'            => array(array('format'=>'%G'), true),
            '13:00'         => array(array('format'=>'%g:%i'), false),
            '24:59'         => array(array('format'=>'%G:%i'), true),
            '25:00'         => array(array('format'=>'%G:%i'), false),
            '25:00'         => array(array('format'=>'%G:%i:%s'), false),
            '121902'        => array(array('format'=>'%m%Y'), true),
            '13120001'      => array(array('format'=>'%d%m%Y'), true)
        );
    function Validate_date( $name )
    {
        $this->PHPUnit_TestCase($name);
    }

    /* will be used later */
    function setup ()
    {
    }

    function tearDown()
    {
    }

    function testDate()
    {
        foreach ($this->dates as $date=>$data){
            $r = Validate::date($date,$data[0]);
            $this->assertEquals($r, $data[1]);
        }
    }
}

// runs the tests
$suite = new PHPUnit_TestSuite("Validate_date");
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();

?>