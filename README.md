# Mage2 Module Experius AlumioLog

    ``experius/module-alumiolog``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Pictures](#markdown-header-pictures)


## Main Functionalities

Shows Alumio logs in Magento 2 Administrator Panel

The module retrieves a task list from Alumio and saves these in Magento. On pageload of a task the extra information about the task will be retrieved from Alumio.

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Experius`
 - Enable the module by running `php bin/magento module:enable Experius_AlumioLog`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require experius/module-alumiolog`
 - enable the module by running `php bin/magento module:enable Experius_AlumioLog`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

There are 3 items to configure:

 - Webservice URL
 - Webservice Bearer Token
 - Routes

Note that on shared Alumio environments, you should add the Routes configuration into your app/etc/config.php - after all, it should not be possible to retrieve other routes in this Magento environment.
Note that the 'obscure' field type is used for the Bearer Token. This means that every configuration save of this page will reset the Bearer Token unfortunately. It's recommended to use `php bin/magento config:set alumiolog/general/webservice_bearer_token {{BEARER TOKEN}} --lock-config`.


## Pictures

![The Grid](/Docs/Images/grid.png)
![The Detailview](/Docs/Images/detail.png)
![The Detail-log-view](/Docs/Images/logdetail.png)
