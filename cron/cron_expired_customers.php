<?php
if (!defined('_PS_ADMIN_DIR_')) {
    define('_PS_ADMIN_DIR_', __DIR__);
}
include _PS_ADMIN_DIR_ . '/../../../config/config.inc.php';

if (isset($_GET['secure_key'])) {
    $secureKey = md5(_COOKIE_KEY_ . Configuration::get('PS_SHOP_NAME'));
    if (!empty($secureKey) && $secureKey === $_GET['secure_key']) {
        echo('delete all here');
    }
}
