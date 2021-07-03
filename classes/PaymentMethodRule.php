<?php

namespace At_com;

/**
 * Class PaymentMethodRuleCore
 */
class PaymentMethodRuleCore extends \ObjectModel
{
    /** @var int Payment Method Rule ID */
    public $id;

    /** @var int Payment Method ID which application belongs to */
    public $id_payment_method = null;

    /** @var string Address first line */
    public $active;

    /** @var bool True if customer is B2B */
    public $rule;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'payment_method_rule',
        'primary' => 'id_payment_method_rule',
        'fields' => [
            'id_payment_method' => ['type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false],
            'active' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false],
            'rule' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'copy_post' => false],
        ],
    ];

    /**
     * PaymentMethodRuleCore constructor.
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
     * Updates the current PaymentMethodRule in the database.
     *
     * @param bool $nullValues Whether we want to use NULL values instead of empty quotes values
     *
     * @return bool Indicates whether the PaymentMethodRule has been successfully updated
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
     * Deletes current PaymentMethodRule from the database.
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
     * @return array PaymentMethodRuleCore
     */
    public static function getPaymentMethodRules()
    {
        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT `id_payment_method_rule`, `id_payment_method`, `active`, `rule`
            FROM `' . _DB_PREFIX_ . 'payment_method_rule`
            ORDER BY `id_payment_method` ASC'
        );
    }

    /**
     * Return customer Payment method rule instance from its id.
     *
     * @param string $id customer id
     *
     * @return bool|PaymentMethodRuleCore PaymentMethodRule instance
     */
    public static function getByPaymentMethodRuleId($id_payment_method_rule = null)
    {

        if ($id_payment_method_rule == null) {
            die(\Tools::displayError());
        }

        $result = \Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
          'SELECT `id_payment_method_rule`, `id_payment_method`, `active`, `rule`
          FROM `' . _DB_PREFIX_ . 'payment_method_rule`
          WHERE `id_payment_method_rule` = \'' . pSQL($id_payment_method_rule) . '\'
          ORDER BY `id_payment_method` ASC'
        );

        if (!$result) {
            return false;
        }

        return new self($result['id_payment_method_rule']);
    }

    /**
     * Return customer Payment method rule instance from its id.
     *
     * @param string $id customer id
     *
     * @return bool|PaymentMethodRuleCore PaymentMethodRule instance
     */
    public static function getByPaymentMethodId($id_payment_method = null)
    {

        if ($id_payment_method == null) {
            die(\Tools::displayError());
        }

        $result = \Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
          'SELECT `id_payment_method_rule`, `id_payment_method`, `active`, `rule`
          FROM `' . _DB_PREFIX_ . 'payment_method_rule`
          WHERE `id_payment_method` = \'' . pSQL($id_payment_method) . '\'
          ORDER BY `id_payment_method` ASC'
        );

        if (!$result) {
            $payment_method_rule = new self();
            $payment_method_rule->id_payment_method = $id_payment_method;
            return $payment_method_rule;
        }

        return new self($result['id_payment_method_rule']);
    }

}
