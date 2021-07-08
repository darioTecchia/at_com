<?php

/**
 * Class Customer
 */
class Customer extends CustomerCore
{
    public $sdi;

    public $vat;

    public $exp_date;

    /**
     * Customer constructor.
     *
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        self::$definition['fields']['sdi'] = ['type' => self::TYPE_STRING, 'required' => false, 'size' => 64];
        self::$definition['fields']['vat'] = ['type' => self::TYPE_STRING, 'required' => false, 'size' => 64];
        self::$definition['fields']['exp_date'] = ['type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false];
        parent::__construct($id);
    }

    public function getCustomerBank() {
        return CustomerApplicationCore::getByCustomerId($this->id);
    }

    public function getCustomerTradeReference() {
        return CustomerTradeReferenceCore::getByCustomerId($this->id);
    }
}
