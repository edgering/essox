<?php
/**
 *  ESSOX API PHP SDK
 * 
 */

require_once("class.eventlog.php");
require_once("class.session.php");
require_once("class.essox.php");

$_ESSOX = new ESSOX();

// $_ESSOX->setProduction(TRUE);
// $_ESSOX->newToken();

// $url = $_ESSOX->getKalkulackaUrl(2300, 666);


$ESSOX_DATA = array(
    "firstName" => "Lorne",
    "surname" => "Balmer",
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

$response = $_ESSOX->getSplatky($ESSOX_DATA);


$_ESSOX->log->debugEvents();
