<?php

class AdminCustomerTradeReferenceController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'customer_trade_reference';
        $this->identifier = 'id_customer_trade_reference';
        $this->className = 'At_com\CustomerTradeReferenceCore';

        parent::__construct();

        $id_lang = $this->context->language->id;

        //data to the grid of the "view" action
        $this->fields_list = [
            'id_customer' => ['title' => $this->l('ID'), 'type' => 'number'],
            'name' => ['title' => $this->l('Name'), 'type' => 'text'],
            'email' => ['title' => $this->l('Email'), 'type' => 'text'],
            'phone' => ['title' => $this->l('Phone'), 'type' => 'text'],
            'phone_mobile' => ['title' => $this->l('Phone mobile'), 'type' => 'text'],
            'buyer_group' => ['title' => $this->l('Buyer Group'), 'type' => 'text'],
        ];

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Customer Trade Reference'),
            ],
            'input' => [
                'id_customer' => [
                    'type' => 'text',
                    'label' => $this->l('ID Customer'),
                    'name' => 'id_customer',
                    'required' => true,
                    'disabled' => true,
                ],
                'name' => [
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'required' => true,
                ],
                'email' => [
                    'type' => 'text',
                    'label' => $this->l('Email'),
                    'name' => 'email',
                    'required' => true,
                ],
                'phone' => [
                    'type' => 'text',
                    'label' => $this->l('Phone'),
                    'name' => 'phone',
                    'required' => true,
                ],
                'phone_mobile' => [
                    'type' => 'text',
                    'label' => $this->l('Phone mobile'),
                    'name' => 'phone_mobile',
                    'required' => true,
                ],
                'buyer_group' => [
                    'type' => 'text',
                    'label' => $this->l('Buyer group'),
                    'name' => 'buyer_group',
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
