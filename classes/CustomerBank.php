<?php

namespace At_com;

/**
 * Class CustomerBankCore
 */
class CustomerBankCore extends \ObjectModel
{
    /** @var int Customer Bank ID */
    public $id;

    /** @var int Customer ID which application belongs to */
    public $id_customer = null;

    /** @var string Address first line */
    public $name;

    /** @var bool True if customer is B2B */
    public $address;

    /** @var bool True if customer is B2C */
    public $iban;

    /** @var string website */
    public $swift;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'customer_bank',
        'primary' => 'id_customer_bank',
        'fields' => [
            'id_customer' => ['type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false],
            'name' => ['type' => self::TYPE_STRING, 'required' => true, 'size' => 255],
            'address' => ['type' => self::TYPE_STRING, 'required' => true, 'size' => 255],
            'iban' => ['type' => self::TYPE_STRING, 'required' => true, 'size' => 34],
            'swift' => ['type' => self::TYPE_STRING, 'required' => true, 'size' => 11],
        ],
    ];

    /**
     * CustomerBankCore constructor.
     *
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * Adds current Customer Bank as a new Object to the database.
     *
     * @param bool $autoDate Automatically set `date_upd` and `date_add` columns
     * @param bool $nullValues Whether we want to use NULL values instead of empty quotes values
     *
     * @return bool Indicates whether the Customer Bank has been successfully added
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
     * Updates the current CustomerBank in the database.
     *
     * @param bool $nullValues Whether we want to use NULL values instead of empty quotes values
     *
     * @return bool Indicates whether the CustomerBank has been successfully updated
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
     * Deletes current CustomerBank from the database.
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
     * Return customer Bank list.
     *
     * @param bool|null $onlyActive Returns only active customers when `true`
     *
     * @return array Customers
     */
    public static function getCustomerBanks()
    {
        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT `id_customer_bank`, `id_customer`, `name`, `address`, `iban`, `swift`
            FROM `' . _DB_PREFIX_ . 'customer_bank`
            ORDER BY `id_customer` ASC'
        );
    }

    /**
     * Return customer Bank instance from its customer id.
     *
     * @param string $id customer id
     *
     * @return bool|CustomerBankCore CustomerBank instance
     */
    public static function getByCustomerId($id_customer = null)
    {

        if ($id_customer == null) {
            die(\Tools::displayError());
        }

        $result = \Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
            'SELECT `id_customer_bank`, `id_customer`, `name`, `address`, `iban`, `swift`
            FROM `' . _DB_PREFIX_ . 'customer_bank`
            WHERE `id_customer` = \'' . pSQL($id_customer) . '\'
            ORDER BY `id_customer` ASC'
        );

        if (!$result) {
            return false;
        }

        return $result;
    }

}
