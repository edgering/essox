<?php

/**
 *  ESSOX SDK 
 * 
 *  08/2023
 * 
 *  ! v testovacím prostředí se používá produkční Client_id a Client_secret
 * 
 */

class ESSOX
{
    var $config = array();

    // store events?

    var $debug = TRUE;
    var $log;

    // (bool) production / sandbox

    var $production = FALSE;

    // (string) token
    var $TOKEN = FALSE;

    /**
     *  SESSION HANDLE
     */

    var $session;

    // (bool) if token should be loaded from session
    var $tokenUseSession = TRUE;

    // (bool) flag if token was loaded from session
    var $tokenFromSession = FALSE;

    // (string) session name for $_SESSION
    var $session_name = "essox_token";

    /**
     *  URLS
     */

    public const url_proposals = "/consumergoods/v1/api/consumergoods/proposal";
    public const url_calculator = "/consumergoods/v1/api/consumergoods/calculator";
    public const url_token = "/token";



    function __construct()
    {
        $this->defaultConfig();

        $this->log = new EventLog();
        $this->session = new Sessions();
    }

    // -- Pro přihlášení do portálu

    function defaultConfig()
    {
        $this->config["production"] = "https://apiv32.essox.cz";
        $this->config["sandbox"] = "https://testapiv32.essox.cz";

        // -- Clint ID a Client Secret

        $this->config["consumer_key_production"] = 'riS6J1RqP62qWigcel1Wws______';
        $this->config["consumer_secret_production"] = 'mk3vwr_hu8xyJsgcaGDze2______';

        // !! V TESTOVACÍM PROSTŘEDÍ SE NEPOUŽÍVÁ SANDBOX ALE PRODUKČNÍ CLIENT ID A CLIENT SECRET ---

        $this->config["consumer_key_sandbox"] = 'ugRZ6fNenPrat_F10imZ9x______';
        $this->config["consumer_secret_sandbox"] = 'PspiO59taLKHjRbFTVm7Cy______';

        /** **/

        $this->config["rozlozena_platba"] = 4;
        $this->config["rozlozena_platba_min_price"] = 2000;
        $this->config["rozlozena_platba_max_price"] = 30000;

        $this->config["splatky_min_price"] = 2000;
        $this->config["splatky_max_price"] = 2000000;
        $this->config["calc_redirect_expire"] = 5 * 60;
    }

    function setProduction($production = TRUE)
    {
        $this->log->event("Production set to: " . ($production ? "TRUE" : "FALSE"));
        $this->production = $production;
    }

    /** **/


    function getApiUrl()
    {
        return $this->production ? $this->config["production"] : $this->config["sandbox"];
    }

    function getClientId()
    {
        return $this->production ? $this->config["consumer_key_production"] : $this->config["consumer_key_sandbox"];
    }

    function getClientSecret()
    {
        return $this->production ? $this->config["consumer_secret_production"] : $this->config["consumer_secret_sandbox"];
    }

    /**
     *  BASIC CURL CALL WRAPPER
     */

    function CURL($url, $headers, $data)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->log->event("CURL CALL: `{$url}`");
        $this->log->event("* Response: " . $httpcode);

        if ($httpcode != 200) {

            $this->log->error("Chyba při získávání dat");
            $this->log->error("curl_error: `" . curl_error($ch) . "`");
            $this->log->error("response: `{$response}`");

            curl_close($ch);

            return FALSE;
        }

        curl_close($ch);

        return json_decode($response, TRUE);
    }

    /** 
     *  Získání URL kalkulačky
     * 
     *  "V odpovědi je vrácena redirectUrl, na kterou je třeba provést přesměrování, na vrácenou URL je možné 
     *  přesměrovat z důvodu bezpečnosti pouze jednou a platnost tokenu je 5 minut."
     * 
     *  + kontrola cen
     */

    function getKalkulackaUrl($price, $item_id = 0)
    {
        if (
            $price < $this->config["splatky_min_price"]
            || $price > $this->config["splatky_max_price"]
        ) {
            $this->log->error("Cena musí být v rozmezí {$this->config["splatky_min_price"]} - {$this->config["splatky_max_price"]} Kč");

            return FALSE;
        }

        if (!$token = $this->getToken()) {

            $this->log->error("Nelze načíst token pro kalkulaci.");

            return FALSE;
        }

        $url = $this->getApiUrl() . self::url_calculator;

        $headers = array(
            'accept: application/json',
            'Content-Type: application/json-patch+json',
            'Authorization: Bearer ' . $token
        );

        $data = json_encode(array(
            'price' => $price,
            'productId' => $item_id,
        ));

        $this->log->event("Request kalkulačka");
        $this->log->event("* Client_id: " . $this->getClientId());
        // $this->event("* Client_secret: " . $this->getClientSecret());

        if ($response = $this->CURL($url, $headers, $data)) {

            // -- Změníme expiraci session údajů na limit tokenu
            // -- je potřeba otestovat zda má token delší platnost než i tak zkrácená verze

            if (!$this->session->testExpires($this->config["calc_redirect_expire"])) {

                $this->session->setSession("expires", time() + $this->config["calc_redirect_expire"]);
            }

            return $response["redirectionUrl"];
        }
    }

    function getCalcUrl($price, $item_id = 0)
    {
        return $this->getKalkulackaUrl($price, $item_id);
    }


    /**
     *  Získání TOKENU
     * 
     *  - allow to override global session setting
     * 
     *  @param bool $forceSession - force to use token from session
     *  $forceSession == TRUE => do not use sessions
     */

    function getToken($forceSession = NULL)
    {
        $this->log->event("Získání tokenu");

        if ($this->TOKEN !== FALSE) {

            $this->log->event("* already set");

            return $this->TOKEN;
        }

        if ($forceSession === NULL) {
            $forceSession = !$this->tokenUseSession;
        }

        if ($forceSession) {
            return $this->requestToken();
        }

        // -- test if sessions have started

        if (!$this->session->testExpires(3 * 60)) {
            $this->TOKEN = $this->session->getSession("token");
            $this->tokenFromSession = TRUE;

            $this->log->event("* získáno ze sessions");

            return $this->TOKEN;
        }

        return $this->requestToken();
    }

    function requestToken()
    {
        $this->TOKEN = FALSE;
        $this->tokenFromSession = FALSE;

        $url = $this->getApiUrl() .  self::url_token;

        $this->log->event("Request TOKEN");
        $this->log->event("* for: " . $this->getClientId());
        $this->log->event("* from: {$url}");

        $token = base64_encode($this->getClientId() . ':' . $this->getClientSecret());

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . $token,
        );

        $data = array(
            'grant_type' => 'client_credentials',
            'scope' => 'scopeFinit.consumerGoods.eshop'
        );

        $data = http_build_query($data);

        if ($response = $this->CURL($url, $headers, $data)) {

            $this->TOKEN = $response["access_token"];


            $this->session->setSession("token", $response["access_token"]);
            $this->session->setSession("expires", time() + $response["expires_in"]);
        }

        return $this->TOKEN;
    }

    function newToken()
    {
        return $this->requestToken(TRUE);
    }

    /**
     *  Rozložená platba 
     * 
     *  return TRUE if rozlozena platba is allowed
     * 
     */

    function getRozlozenaPlatba($cena = NULL)
    {
        if (!isset($this->config["rozlozena_platba"])) {
            return FALSE;
        }

        if ($cena === NULL) {
            return $this->config["rozlozena_platba"];
        }

        if (
            $cena < $this->config["rozlozena_platba_min_price"]
            || $cena > $this->config["rozlozena_platba_max_price"]
        ) {
            return FALSE;
        }

        return round($cena / $this->config["rozlozena_platba"], 2);
    }

    /**
     *  SPLÁTKY
     */

    function getSplatky($data)
    {
        if (!$token = $this->getToken()) {

            $this->log->error("Nelze načíst token pro splátky.");

            return FALSE;
        }

        $url = $this->getApiUrl() . self::url_proposals;

        $headers = array(
            'accept: application/json',
            'Content-Type: application/json-patch+json',
            'Authorization: Bearer ' . $token
        );

        if (is_array($data)) {
            $data = (object) $data;
        }

        $data = json_encode($data);

        if ($response = $this->CURL($url, $headers, $data)) {

            return $response;
        }

        return FALSE;
    }

    function getSplatkyParams()
    {
?>
        <code>
            {
            "firstName": "string",
            "surname": "string",
            "mobilePhonePrefix": "string",
            "mobilePhoneNumber": "string",
            "email": "string",
            "price": 0,
            "productId": 0,
            "orderId": "string",
            "customerId": "string",
            "transactionId": "string",
            "shippingAddress": {
            "street": "string",
            "houseNumber": "string",
            "city": "string",
            "zip": "string"
            },
            "callbackUrl": "string",
            "spreadedInstalments": true
            }
        </code>

<?php
    }

    /**
     *  SESSION HANDLE
     */

    function disableTokenSession()
    {
        $this->tokenUseSession = FALSE;
        $this->session->disable = TRUE;
    }

    function setSessionName($name)
    {
        $this->session->setSessionName($name);
    }

    /**
     *  Allow to read config from array
     */

    function readConfig($source)
    {
        foreach ($source as $key => $value) {
            $this->config[$key] = $value;

            if (isset($this->$key)) {
                $this->$key = $value;
            }
        }
    }
}
