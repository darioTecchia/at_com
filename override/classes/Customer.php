<?php

/**
 * Class Customer
 */
class Customer extends CustomerCore
{
    public $sdi;

    public $vat;

    /**
     * Customer constructor.
     *
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        self::$definition['fields']['sdi'] = ['type' => self::TYPE_STRING, 'required' => true, 'size' => 64];
        self::$definition['fields']['vat'] = ['type' => self::TYPE_STRING, 'required' => true, 'size' => 64];
        parent::__construct($id);
    }

    public function getCustomerBank() {
        return CustomerApplicationCore::getByCustomerId($this->id);
    }

    public function getCustomerTradeReference() {
        return CustomerTradeReferenceCore::getByCustomerId($this->id);
    }
}
