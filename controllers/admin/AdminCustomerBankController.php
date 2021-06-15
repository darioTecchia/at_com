<?php

class AdminCustomerBankController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'customer_bank';
        $this->identifier = 'id_customer_bank';
        $this->className = 'At_com\CustomerBankCore';

        parent::__construct();

        $id_lang = $this->context->language->id;

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
