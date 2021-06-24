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
            'id_customer_bank' => ['title' => $this->l('ID'), 'type' => 'number'],
            'id_customer' => ['title' => $this->l('Customer'), 'type' => 'number'],
            'name' => ['title' => $this->l('Name'), 'type' => 'text'],
            'address' => ['title' => $this->l('Address'), 'type' => 'text'],
            'iban' => ['title' => $this->l('Iban'), 'type' => 'text'],
            'swift' => ['title' => $this->l('Swift'), 'type' => 'text'],
        ];

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Customer Bank'),
            ],
            'input' => [
                'id_customer' => [
                    'type' => 'hidden',
                    'label' => $this->l('ID Customer'),
                    'name' => 'id_customer',
                    'required' => true,
                ],
                'name' => [
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'required' => true,
                ],
                'address' => [
                    'type' => 'text',
                    'label' => $this->l('Address'),
                    'name' => 'address',
                    'required' => true,
                ],
                'iban' => [
                    'type' => 'text',
                    'label' => $this->l('IBAN'),
                    'name' => 'iban',
                    'required' => true,
                ],
                'swift' => [
                    'type' => 'text',
                    'label' => $this->l('Swift'),
                    'name' => 'swift',
                    'required' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];
    }

    public function initContent()
    {
        parent::initContent();
    }
}
