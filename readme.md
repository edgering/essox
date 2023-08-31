# Implementace ESSOX - API v PHP

    $_ESSOX = new Essox();

    // turn off using session for token

    // $_ESSOX->tokenUseSession = FALSE;

    // set own session name

    // $_ESSOX->session_name = "name_of_session";

    // -- to set base config data

    $_ESSOX->readConfig($CONFIG);

    $CONFIG = array(
        "consumer_key_production" => 'riS6J1RqP62qWigcel1WwsSddeAa',
        "consumer_secret_production" => 'mk3vwr_hu8xyJsgcaGDze2PwVUwa',

        "consumer_key_sandbox" => 'ugRZ6fNenPrat_F10imZ9xtQT5ca',
        "consumer_secret_sandbox" => 'PspiO59taLKHjRbFTVm7CyZ7k1sa',

        "rozlozena_platba" => 4,
        "rozlozena_platba_min_price" => 2000,
        "rozlozena_platba_max_price" => 30000,

        "splatky_min_price" => 2000,
        "splatky_max_price" => 2000000,
        "calc_redirect_expire" => 5 * 60,
    );        


## Získání Tokenu

    (string)$_ESSOX->newToken();

Není potřeba volat samostatně. Automaticky se provede při prvním volání API.

## Odkaz na kalkulačku

    // -- cena, id produktu

    (string)$_ESSOX->calcLink(2000, 157);

## Odkaz na splátky + odeslání žádosti
    
    $data = array()

    // $_ESSOX->getSplatkyParams();

    (array)$_ESSOX->calcLink($data);

## Odkazy

- Produkční prostředí: https://apiv32.essox.cz/
- Testovací prostředí: https://testapiv32.essox.cz/

### Portál pro testování a API management

- Produkční credentials: https://developersv32.essox.cz/devportal/
- Testovací credentials: https://testdevelopersv32.essox.cz/devportal/
