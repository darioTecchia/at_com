<?php

namespace At_com;

/**
 * Class CustomerApplicationCore
 */
class CustomerApplicationCore extends \ObjectModel
{
    /** @var int Customer Application ID */
    public $id;

    /** @var int Customer ID which application belongs to */
    public $id_customer = null;

    /** @var string Address first line */
    public $brands;

    /** @var bool True if customer is B2B */
    public $b2b;

    /** @var bool True if customer is B2C */
    public $b2c;

    /** @var string website */
    public $website;

    /** @var string Amazon seller name */
    public $amazon;

    /** @var string Ebay seller name */
    public $ebay;

    /** @var string Other marketplace names */
    public $other;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'customer_application',
        'primary' => 'id_customer_application',
        'fields' => [
            'id_customer' => ['type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false],
            'brands' => ['type' => self::TYPE_STRING, 'required' => true, 'size' => 255],
            'b2b' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false],
            'b2c' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false],
            'website' => ['type' => self::TYPE_STRING, 'validate' => 'isUrl'],
            'amazon' => ['type' => self::TYPE_STRING, 'size' => 128],
            'ebay' => ['type' => self::TYPE_STRING, 'size' => 128],
            'other' => ['type' => self::TYPE_STRING, 'size' => 128],
        ],
    ];

    /**
     * CustomerApplicationCore constructor.
     *
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * Adds current Customer Application as a new Object to the database.
     *
     * @param bool $autoDate Automatically set `date_upd` and `date_add` columns
     * @param bool $nullValues Whether we want to use NULL values instead of empty quotes values
     *
     * @return bool Indicates whether the Customer Application has been successfully added
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
     * Updates the current CustomerApplication in the database.
     *
     * @param bool $nullValues Whether we want to use NULL values instead of empty quotes values
     *
     * @return bool Indicates whether the CustomerApplication has been successfully updated
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
     * Deletes current CustomerApplication from the database.
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
     * Return customer application list.
     *
     * @param bool|null $onlyActive Returns only active customers when `true`
     *
     * @return array Customers
     */
    public static function getCustomerApplications()
    {
        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT `id_customer_application`, `id_customer`, `brands`, `b2b`, `b2c`, `website`, `amazon`, `ebay`, `other`
            FROM `' . _DB_PREFIX_ . 'customer_application`
            ORDER BY `id_customer` ASC'
        );
    }

    /**
     * Return customer application instance from its customer id.
     *
     * @param string $id customer id
     *
     * @return bool|CustomerApplicationCore CustomerApplication instance
     */
    public static function getByCustomerId($id_customer = null)
    {

        if ($id_customer == null) {
            die(\Tools::displayError());
        }

        $result = \Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
            'SELECT `id_customer_application`, `id_customer`, `brands`, `b2b`, `b2c`, `website`, `amazon`, `ebay`, `other`
            FROM `' . _DB_PREFIX_ . 'customer_application`
            WHERE `id_customer` = \'' . pSQL($id_customer) . '\'
            ORDER BY `id_customer` ASC'
        );

        if (!$result) {
            return false;
        }

        return $result;
    }

}
