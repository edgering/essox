# Implementace ESSOX - API v PHP

(update 2023-09-04)

    $_ESSOX = new Essox();

##Zakázat používání session pro ukládání tokenu

    $_ESSOX->disableTokenSession();

##Nastavit jméno session pro ukládání tokenu. (Default je "essox_token")

    $_ESSOX->session_name = "name_of_session";

##Načtení konfigurace z pole

funguje klasicky jako přepsání defaultní konfigurace. 

    $_ESSOX->readConfig((array)$CONFIG);

###Zobrazení konfigurace

    $_ESSOX->showConfig();    

###Minimální tvar konfigu

    $CONFIG = array(
        
        /**
         *  PRODUKČNÍ KLÍČE z produkčního prostředí.
         */

        "consumer_key_production" => 'riS6J1RqP62qWigcel1WwsSddeAa',
        "consumer_secret_production" => 'mk3vwr_hu8xyJsgcaGDze2PwVUwa',

        /**
         *  PRODUKČNÍ KLÍČE z testovacího prostředí.         
         */

        "consumer_key_sandbox" => 'ugRZ6fNenPrat_F10imZ9x______',
        "consumer_secret_sandbox" => 'PspiO59taLKHjRbFTVm7Cy______',
    );        

##Zapnutí produkční verze. Default je sandbox.

    $_ESSOX->setProduction();


## Získání Tokenu

    (string)$_ESSOX->newToken();

Není potřeba volat samostatně. Automaticky se provede při prvním volání API.   

Toto volání resetuje časový limit platnosti tokenu od začátku


## Odkaz na kalkulačku

    // -- cena, id produktu

    (string)$_ESSOX->calcLink(2000, 157);

## Odkaz na splátky + odeslání žádosti
    
    $data = array()

    // $_ESSOX->getSplatkyParams();

    (FALSE || array) $_ESSOX->calcLink($data);

## Rozložená platba

Zjistí, zda je možné rozložit platbu na splátky. Pokud ano, vrátí výši splátky.
    
    (FALSE || float) $_ESSOX->getRozlozenaPlatba((float)$cena);

## Odkazy

- Produkční prostředí: https://apiv32.essox.cz/
- Testovací prostředí: https://testapiv32.essox.cz/

### Portál pro testování a API management

- Produkční credentials: https://developersv32.essox.cz/devportal/
- Testovací credentials: https://testdevelopersv32.essox.cz/devportal/
