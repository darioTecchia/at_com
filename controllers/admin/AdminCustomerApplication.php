<?php

class AdminCustomerApplicationController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'customer_application';
        $this->identifier = 'id_customer_application';
        $this->className = 'At_com\CustomerApplicationCore';

        parent::__construct();

        $id_lang = $this->context->language->id;

        //data to the grid of the "view" action
        $this->fields_list = [
            'id_customer' => ['title' => $this->l('ID'), 'type' => 'number'],
            'brands' => ['title' => $this->l('Brands'), 'type' => 'text'],
            'b2b' => ['title' => $this->l('B2B'), 'type' => 'bool'],
            'b2c' => ['title' => $this->l('B2C'), 'type' => 'bool'],
            'website' => ['title' => $this->l('Website'), 'type' => 'text'],
            'amazon' => ['title' => $this->l('Amazon'), 'type' => 'text'],
            'ebay' => ['title' => $this->l('EBay'), 'type' => 'text'],
            'other' => ['title' => $this->l('Other'), 'type' => 'text'],
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
