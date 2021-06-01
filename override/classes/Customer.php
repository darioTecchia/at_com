<?php

/**
 * Class Customer
 */
class Customer extends CustomerCore
{
    public $sdi;

    public $pec;

    public $vat;

    /**
     * Customer constructor.
     *
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        self::$definition['fields']['sdi'] = ['type' => self::TYPE_STRING, 'required' => true, 'size' => 64];
        self::$definition['fields']['pec'] = ['type' => self::TYPE_STRING, 'validate' => 'isEmail', 'required' => true, 'size' => 255];
        self::$definition['fields']['var'] = ['type' => self::TYPE_STRING, 'required' => true, 'size' => 64];
        parent::__construct($id);
    }

    public function getCustomerBank() {
        return CustomerApplicationCore::getByCustomerId($this->id);
    }

    public function getCustomerTradeReference() {
        return CustomerTradeReferenceCore::getByCustomerId($this->id);
    }
}
