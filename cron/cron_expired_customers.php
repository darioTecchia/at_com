<?php
if (!defined('_PS_ADMIN_DIR_')) {
    define('_PS_ADMIN_DIR_', __DIR__);
}
include _PS_ADMIN_DIR_ . '/../../../config/config.inc.php';

if (isset($_GET['secure_key'])) {
    $secureKey = md5(_COOKIE_KEY_ . Configuration::get('PS_SHOP_NAME'));
    if (!empty($secureKey) && $secureKey === $_GET['secure_key']) {
        $customers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT `id_customer`, `exp_date`
            FROM `' . _DB_PREFIX_ . 'customer`
            WHERE 1 ' . Shop::addSqlRestriction(Shop::SHARE_CUSTOMER) .
            'AND `exp_date` <= NOW()' .
            'ORDER BY `id_customer` ASC'
        );
        $success = true;
        foreach ($customers as $customer) {
            $customerObj = new Customer($customer['id_customer']);
            $customerObj->active = 0;
            $success &= $customerObj->update();
        }
        echo($success);
    }
}
