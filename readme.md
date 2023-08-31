# Třída pro implementaci kalkulace spotřebního úvěru ESSOX

    $_ESSOX => new Essox();

## Získání Tokenu

    $_ESSOX->newToken();

## Odkaz na kalkulačku

    $_ESSOX->calcLink();

## Odkaz na splátky + odeslání žádosti
    
    $data = array()

    // $_ESSOX->getSplatkyParams();

    $_ESSOX->calcLink($data);

## Odkazy

### Endpoint API kalkulace: 

- Produkční prostředí: https://apiv32.essox.cz/consumergoods/v1/api/consumergoods/calculator
- Testovací prostředí: https://testapiv32.essox.cz/consumergoods/v1/api/consumergoods/calculator