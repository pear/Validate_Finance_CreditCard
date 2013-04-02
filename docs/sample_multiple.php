<?php

require_once 'Validate/Finance/CreditCard.php';

$values = array(
    '6762195515061813',
    '6762195515061814',
);

foreach ($values as $value) {
    $result = Validate_Finance_CreditCard::number($values);
    print_r($result);
}

?>
