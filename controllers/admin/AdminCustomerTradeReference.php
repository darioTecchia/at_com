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
