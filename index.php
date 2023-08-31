<?php

require_once("class.eventlog.php");
require_once("class.session.php");
require_once("class.essox.php");

$_ESSOX = new ESSOX();

// $_ESSOX->newToken();
// $_ESSOX->setProduction(TRUE);

// echo $_ESSOX->getSplatky();
// echo $_ESSOX->getKalkulackaUrl(2300, 666);

$ESSOX_DATA = array(
    "firstName" => "Jirka",
    "surname" => "Kopřiva",
    "mobilePhonePrefix" => "+420",
    "mobilePhoneNumber" => "775123456",
    "email" => "tester@edgering.org",
    "price" => 3500,
    "productId" => "108233",
    "orderId" => "230001",
    "customerId" => 666,
    "transactionId" => "string-string-string",
    "shippingAddress" => array(
        "street" => "AB",
        "houseNumber" => "22",
        "city" => "Budweis",
        "zip" => "37005",
    ),
    "callbackUrl" => "https://www.edgering.org/essox/callback.php",
    "spreadedInstalments" => true,
);

echo $_ESSOX->getSplatky($ESSOX_DATA);

//echo $_ESSOX->getToken();

$_ESSOX->log->debugEvents();
