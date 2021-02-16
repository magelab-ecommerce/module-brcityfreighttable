# module-brcityfreighttable
Magento 2 Shipping module for BR cities table

This module use https://viacep.com.br/ API to find the `city` and `UF` from zipcode. The result is stored in cache for all users. You can set many shippment methods to be enabled for the same city. `|` Esse módulo usa a API https://viacep.com.br/ para encontrar a `cidade` e o `UF` a partir do CEP. O resultado é armazenado no cache para todos os usuários. É possível criar vários métodos de envio para a mesma cidade.

Install `|` Instalação:

```bash
composer require magelab-ecommerce/module-brcityfreighttable
```

```bash
php bin/magento setup:upgrade;
```

```bash
php bin/magento setup:di:compile
```

```bash
php bin/magento setup:di:compile
```

```bash
php bin/magento c:c
```




Contact `|` Contato
- Pedro Lima
- phgdl.19@gmail.com
