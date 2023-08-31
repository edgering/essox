# Implementace ESSOX - API v PHP

    $_ESSOX = new Essox();

    // turn off using session for token

    $_ESSOX->tokenUseSession = FALSE;

    // set own session name

    $_ESSOX->session_name = "name_of_session";

    // to set config data

    $_ESSOX->readConfig($CONFIG);

config mandatory:

    $CONFIG = array(
        
        /**
         *  PRODUKČNÍ KLÍČE z produkčního prostředí.
         */

        "consumer_key_production" => 'riS6J1RqP62qWigcel1WwsSddeAa',
        "consumer_secret_production" => 'mk3vwr_hu8xyJsgcaGDze2PwVUwa',

        /**
         *  PRODUKČNÍ KLÍČE z testovacího prostředí.
         * 
         */

        "consumer_key_sandbox" => 'ugRZ6fNenPrat_F10imZ9x______',
        "consumer_secret_sandbox" => 'PspiO59taLKHjRbFTVm7Cy______',
    );        

    // Zapnutí produkční verze. Default je sandbox.

    $_ESSOX->setProduction();


## Získání Tokenu

    (string)$_ESSOX->newToken();

Není potřeba volat samostatně. Automaticky se provede při prvním volání API.

## Odkaz na kalkulačku

    // -- cena, id produktu

    (string)$_ESSOX->calcLink(2000, 157);

## Odkaz na splátky + odeslání žádosti
    
    $data = array()

    // $_ESSOX->getSplatkyParams();

    $result = (FALSE || array)$_ESSOX->calcLink($data);

## Rozložená platba

Zjistí, zda je možné rozložit platbu na splátky. Pokud ano, vrátí výši splátky.
    
    (FALSE || float) $_ESSOX->getRozlozenaPlatba((float)$cena);

## Odkazy

- Produkční prostředí: https://apiv32.essox.cz/
- Testovací prostředí: https://testapiv32.essox.cz/

### Portál pro testování a API management

- Produkční credentials: https://developersv32.essox.cz/devportal/
- Testovací credentials: https://testdevelopersv32.essox.cz/devportal/
