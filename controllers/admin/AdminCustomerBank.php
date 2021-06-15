<?php
require_once _PS_MODULE_DIR_ . 'at_com_module/classes/CustomerBank.php';

use At_com\CustomerBankCore as CustomerBank;

class AdminCustomerBank extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'customer_bank';
        $this->identifier = 'id_customer_bank';
        $this->className = 'CustomerBank';

        parent::__construct();

        $id_lang = $this->context->language->id;

        // $this->_join .= "LEFT JOIN " . _DB_PREFIX_ . "order_state_lang b ON 2 = b.id_order_state AND b.id_lang  = " . $id_lang;
        // $this->_join .= " LEFT JOIN " . _DB_PREFIX_ . "carrier c ON a.id_carrier  = c.id_carrier";
        // $this->_join .= " LEFT JOIN " . _DB_PREFIX_ . "marketplace_order_shipping_address d ON a.id_order_address = d.id_marketplace_order_shipping_address";
        // $this->_select .= 'a.id_marketplace_order, a.reference, d.nation, c.name AS carrier_name, d.email, a.total_paid, b.name AS status, a.shipping_number, a.delivery_date';

        //data to the grid of the "view" action
        $this->fields_list = [
            'id_customer' => ['title' => $this->l('ID'), 'type' => 'number'],
            'name' => ['title' => $this->l('Name'), 'type' => 'text'],
            'address' => ['title' => $this->l('Address'), 'type' => 'text'],
            'iban' => ['title' => $this->l('Iban'), 'type' => 'text'],
            'swift' => ['title' => $this->l('Swift'), 'type' => 'text'],
        ];
    }

    public function renderView()
    {
        return;
    }

    public function postProcess()
    {
        return;
    }

    public function initContent()
    {
        parent::initContent();
    }
}
