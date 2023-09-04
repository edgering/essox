<?php

/**
 *  ESSOX API SDK
 * 
 *     init
 * 
 */

require_once(__DIR__ . "/class.eventlog.php");
require_once(__DIR__ . "/class.session.php");
require_once(__DIR__ . "/class.essox.php");

$_ESSOX = new ESSOX();

/*
include_once(__DIR__ . "/config.php");

if (isset($CFG["ESSOX"])) {
    $_ESSOX->readConfig($CFG["ESSOX"]);
}
*/
