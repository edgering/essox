<?php
/**
 *  ESSOX API PHP SDK
 * 
 */

include __DIR__ . "/init.php";

include_once(__DIR__ . "/config.php");

$_ESSOX->readConfig($CFG["ESSOX"]);

// -- unset storing event messages

// $_ESSOX->debug = FAlSE;

// -- set production mode

// $_ESSOX->setProduction();

// -- reset token (update session stamp)

// $_ESSOX->newToken();

// $url = $_ESSOX->getKalkulackaUrl(2300, 666);


$ESSOX_DATA = array(
    "firstName" => "Lorne",
    "surname" => "Balmer",
    "mobilePhonePrefix" => "+420",
    "mobilePhoneNumber" => "775123456",
    "email" => "tester@edgering.org",
    "price" => 3500,
    "productId" => 0,
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

    // -- e-shop je musí mít povolený

    "spreadedInstalments" => false,
);

$response = $_ESSOX->getSplatky($ESSOX_DATA);

print_r($response);

$_ESSOX->log->debugEvents();
