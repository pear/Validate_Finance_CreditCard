<?php
require_once 'PHPUnit.php';
require_once 'Validate.php';

// This test needs Validate_Finance_CreditCard installed to work
if (!@include_once 'Validate/Finance/CreditCard.php') {
    exit('Please install Validate_Finance_CreditCard');
}

class Validate_multiple_Test extends PHPUnit_TestCase
{
    var $data  = array(
                       array(array('myemail' => 'webmaster@google.com'),
                             array('myemail' => true)),                     // expected return value
                       array(array('myemail1' => 'webmaster.@google.com'),
                             array('myemail1' => false)),                     // expected return value
                       array(array('no' => '-8'),
                             array('no' => true)),                          // expected return value
                       array(array('teststring' => 'PEARrocks'),
                             array('teststring' => true)),                  // expected return value
                       array(array('date' => '12121996'),
                             array('date' => true)),                        // expected return value
                       array(array('cc_no' => '6762 1955 1506 1813'),
                             array('cc_no' => true))                        // expected return value
                      );

    var $types = array(
                       array(array('myemail'    => array('type' => 'email'))),
                       array(array('myemail1'    => array('type' => 'email'))),
                       array(array('no'         => array('type' => 'number', array('min' => -8, 'max' => -7)))),
                       array(array('teststring' => array('type' => 'string', array('format' => VALIDATE_ALPHA)))),
                       array(array('date'       => array('type' => 'date',   array('format' => '%d%m%Y')))),
                       array(array('cc_no'      => array('type' => 'Finance_CreditCard_number')))
                      );

    function Validate_multiple_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function testMultiple()
    {
        foreach ($this->data as $key => $value) {
            $this->assertEquals(Validate::multiple($value[0], $this->types[$key][0]), $value[1]);
        }
    }
}

// runs the tests
$suite = new PHPUnit_TestSuite('Validate_multiple_Test');
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();

