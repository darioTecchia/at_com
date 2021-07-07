<?php

namespace At_com;

/**
 * Class CustomerTradeReferenceCore
 */
class CustomerTradeReferenceCore extends \ObjectModel
{
    /** @var int Customer Trade Reference ID */
    public $id;

    /** @var int Customer ID which trade reference belongs to */
    public $id_customer = null;

    /** @var string Customer trade reference name */
    public $name;

    /** @var string Email */
    public $email;

    /** @var string Phone number */
    public $phone;

    /** @var string Mobile phone number */
    public $phone_mobile;

    /** @var string Amazon seller name */
    public $buyer_group;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'customer_trade_reference',
        'primary' => 'id_customer_trade_reference',
        'fields' => [
            'id_customer' => ['type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false],
            'name' => ['type' => self::TYPE_STRING, 'required' => true, 'size' => 255],
            'email' => ['type' => self::TYPE_STRING, 'validate' => 'isEmail', 'required' => true, 'size' => 255],
            'phone' => ['type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'required' => true, 'size' => 32],
            'phone_mobile' => ['type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'required' => true, 'size' => 32],
            'buyer_group' => ['type' => self::TYPE_STRING, 'size' => 255],
        ],
    ];

    /**
     * CustomerTradeReferenceCore constructor.
     *
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * Adds current Customer Trade Reference as a new Object to the database.
     *
     * @param bool $autoDate Automatically set `date_upd` and `date_add` columns
     * @param bool $nullValues Whether we want to use NULL values instead of empty quotes values
     *
     * @return bool Indicates whether the Customer Trade Reference has been successfully added
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function add($autoDate = true, $nullValues = true)
    {
        $success = parent::add($autoDate, $nullValues);
        return $success;
    }

    /**
     * Updates the current CustomerTradeReference in the database.
     *
     * @param bool $nullValues Whether we want to use NULL values instead of empty quotes values
     *
     * @return bool Indicates whether the CustomerTradeReference has been successfully updated
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function update($nullValues = false)
    {
        try {
            return parent::update(true);
        } catch (\PrestaShopException $exception) {
            $message = $exception->getMessage();
            error_log($message);

            return false;
        }
    }

    /**
     * Deletes current CustomerTradeReference from the database.
     *
     * @return bool True if delete was successful
     *
     * @throws PrestaShopException
     */
    public function delete()
    {
        return parent::delete();
    }

    /**
     * Return customer trade reference list.
     *
     * @param bool|null $onlyActive Returns only active customers when `true`
     *
     * @return array Customers
     */
    public static function getCustomerTradeReferences()
    {
        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT `id_customer_trade_reference`, `id_customer`, `name`, `email`, `phone`, `phone_mobile`, `buyer_group`
            FROM `' . _DB_PREFIX_ . 'customer_trade_reference`
            ORDER BY `id_customer` ASC'
        );
    }

    /**
     * Return customer trade reference instance from its customer id.
     *
     * @param string $id customer id
     *
     * @return bool|CustomerTradeReferenceCore CustomerTradeReference instance
     */
    public static function getByCustomerId($id_customer = null)
    {

        if ($id_customer == null) {
            die(\Tools::displayError());
        }

        $result = \Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
            'SELECT `id_customer_trade_reference`, `id_customer`, `name`, `email`, `phone`, `phone_mobile`, `buyer_group`
            FROM `' . _DB_PREFIX_ . 'customer_trade_reference`
            WHERE `id_customer` = \'' . pSQL($id_customer) . '\'
            ORDER BY `id_customer` ASC'
        );

        if (!$result) {
            return false;
        }

        return $result;
    }

}
