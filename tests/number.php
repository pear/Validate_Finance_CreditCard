<?php
require_once 'Validate.php';
require_once 'PHPUnit.php';

class Validate_Number extends PHPUnit_TestCase
{
    var $numbers = array(
                    8       => array(null,true),
                    '-8'    => array(null,true),
                    -8      => array(null,true),
                    '-8,'   => array(array('decimal'=>','), false),
                    '-8.0'  => array(array('decimal'=>','), false),
                    '-8,0'  => array(array('decimal'=>',','dec_prec'=>2),
                                true),
                    8.0004  => array(array('decimal'=>'.','dec_prec'=>3),
                                false),
                    8.0004  => array(array('decimal'=>'.','dec_prec'=>4),
                                true),
                    '-8'    => array(array('min'=>1,'max'=>9), false),
                    '-8'    => array(array('min'=>-8,'max'=>-7), true),
                    '-8.02' => array(array('decimal'=>'.',
                                'min'=>-8,'max'=>-7), false),
                    '-8.02' => array(array('decimal'=>'.',
                                'min'=>-9,'max'=>-7), true),
                    '-8.02' => array(array('decimal'=>'.,',
                                'min'=>-9,'max'=>-7), true)
                );

    function Validate_Number( $name )
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

    /* Is there a way to get which loop iteration failed?
     * No time to investigate, feel free to modify
     */
    function testNumber()
    {
        foreach($this->numbers as $number=>$data) {
            $r = Validate::number($number,$data[0]);
            $this->assertEquals($r, $data[1]);
        }
    }
}


// runs the tests
$suite = new PHPUnit_TestSuite("Validate_Number");
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();

?>
