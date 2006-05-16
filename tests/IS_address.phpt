--TEST--
Validate_IS::address()
--FILE--
<?php
    error_reporting(E_ALL & ~E_STRICT);
    require_once "Validate/IS.php";

    $addresses = array(
        "Reglubraut"    => 1,
        "Mánagata"      => 7,
        "Aðalstræti"    => 6,
        "Sæbólsbraut"   => 1,
        "Bógus"         => 0,
        "Vestmannabraut" => 1
    );

    foreach($addresses as $address => $count) {
        $result = Validate_IS::address($address);
        printf("%-20s: %d (%d)\n", $address, is_array($result) ? count($result) : 0, $count);
    }

    print "\n";
    
    foreach($addresses as $address => $count) {
        $result = Validate_IS::address($address, 200);
        printf("%-20s: %d\n", $address, is_array($result) ? count($result) : 0);
    }
?>
--EXPECT--
Reglubraut          : 1 (1)
Mánagata            : 7 (7)
Aðalstræti          : 6 (6)
Sæbólsbraut         : 1 (1)
Bógus               : 0 (0)
Vestmannabraut      : 1 (1)

Reglubraut          : 0
Mánagata            : 0
Aðalstræti          : 0
Sæbólsbraut         : 1
Bógus               : 0
Vestmannabraut      : 0
