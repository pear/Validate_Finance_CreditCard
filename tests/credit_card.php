<?php
require_once 'PHPUnit.php';
require_once 'Validate.php';

class Validate_CreditCard extends PHPUnit_TestCase
{
    var $cards = array(
                    0   => false,
                    -1  => false,
                    '6762195515061813'  => true,
                    '6762195515061814'  => false,
                    '4405771709596026'  => true,
                    'Big brother is watching you' => false
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

    function testCreditCard()
    {
        foreach ($this->cards as $card=>$expected_result){
            $r = Validate::creditCard($card);
            $this->assertEquals($r, $expected_result);
        }
    }
}

// runs the tests
$suite = new PHPUnit_TestSuite("Validate_CreditCard");
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();

?>