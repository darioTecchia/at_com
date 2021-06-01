<?php
/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2021 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
$sql = array();

$sql[] =
    'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'customer_application` (
    `id_customer_application` int(10) NOT NULL auto_increment,
    `id_customer` int(10) unsigned NOT NULL DEFAULT 0,
    `brands` varchar(255) NOT NULL,
    `b2b` tinyint(1) unsigned NOT NULL DEFAULT 0,
    `b2c` tinyint(1) unsigned NOT NULL DEFAULT 0,
    `website` varchar(128),
    `amazon` varchar(128),
    `ebay` varchar(128),
    `other` varchar(128),
    PRIMARY KEY  (`id_customer_application`)
    KEY `id_customer` (`id_customer`),
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] =
    'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'customer_bank` (
    `id_customer_bank` int(10) NOT NULL auto_increment,
    `id_customer` int(10) unsigned NOT NULL DEFAULT 0,
    `name` varchar(255) NOT NULL,
    `address` varchar(255) NOT NULL,
    `iban` varchar(34) NOT NULL,
    `swift` varchar(11) NOT NULL,
    PRIMARY KEY  (`id_customer_bank`)
    KEY `id_customer` (`id_customer`),
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] =
    'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'customer_trade_reference` (
    `id_customer_trade_reference` int(10) NOT NULL auto_increment,
    `id_customer` int(10) unsigned NOT NULL DEFAULT 0,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone` varchar(32) DEFAULT NULL,
    `phone_mobile` varchar(32) DEFAULT NULL,
    `buyer_group` varchar(255) NOT NULL,
    PRIMARY KEY  (`id_customer_trade_reference`)
    KEY `id_customer` (`id_customer`),
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'customer` ADD `sdi` varchar(64);';
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'customer` ADD `pec` varchar(64);';
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'customer` ADD `vat` varchar(64);';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
