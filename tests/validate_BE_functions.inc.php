<?php

function test_func($func_name, $data ,$scndParam=NULL)
{
    global $noYes;
    $noYes = array('KO', 'OK');

    echo "---------\nTest " . $func_name . "\n";
    echo ' _ Value                  State Return' . "\n";
    echo ' V = validation result is right' . "\n";
    echo ' X = validation result is wrong' . "\n";

    foreach ($data as $value => $resultWaited) {

        if(!is_null($scndParam))
        {
            $result = $noYes[Validate_BE::$func_name($value,$scndParam)];
        }
        else

        $result = $noYes[Validate_BE::$func_name($value)];

        echo  ($resultWaited == $result
        ? ' V '
        : '!X!')
        .str_pad($value, 20) . " : ".$resultWaited . '    ' . $result ."\n";

    }
    return ($resultWaited != $result );

}

?>