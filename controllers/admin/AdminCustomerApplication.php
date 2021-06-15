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
            'id_customer_application' => ['title' => $this->l('ID Customer Application'), 'type' => 'number'],
            'id_customer' => ['title' => $this->l('ID'), 'type' => 'number'],
            'brands' => ['title' => $this->l('Brands'), 'type' => 'text'],
            'b2b' => ['title' => $this->l('B2B'), 'type' => 'bool'],
            'b2c' => ['title' => $this->l('B2C'), 'type' => 'bool'],
            'website' => ['title' => $this->l('Website'), 'type' => 'text'],
            'amazon' => ['title' => $this->l('Amazon'), 'type' => 'text'],
            'ebay' => ['title' => $this->l('EBay'), 'type' => 'text'],
            'other' => ['title' => $this->l('Other'), 'type' => 'text'],
        ];

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Customer Application'),
            ],
            'input' => [
                'id_customer' => [
                    'type' => 'text',
                    'label' => $this->l('ID Customer'),
                    'name' => 'id_customer',
                    'required' => true,
                    'disabled' => true
                ],
                'brands' => [
                    'type' => 'text',
                    'label' => $this->l('Brands'),
                    'name' => 'brands',
                    'required' => true,
                ],
                'b2b' => [
                    'type' => 'radio',
                    'label' => $this->l('B2B'),
                    'name' => 'b2b',
                    'required' => true,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'b2b_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'b2b_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ],
                'b2c' => [
                    'type' => 'radio',
                    'label' => $this->l('B2C'),
                    'name' => 'b2c',
                    'required' => true,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'b2c_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'b2c_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ],
                'website' => [
                    'type' => 'text',
                    'label' => $this->l('Website'),
                    'name' => 'website',
                    'required' => true,
                ],
                'amazon' => [
                    'type' => 'text',
                    'label' => $this->l('Amazon'),
                    'name' => 'amazon',
                    'required' => true,
                ],
                'ebay' => [
                    'type' => 'text',
                    'label' => $this->l('Ebay'),
                    'name' => 'ebay',
                    'required' => true,
                ],
                'other' => [
                    'type' => 'text',
                    'label' => $this->l('Other'),
                    'name' => 'other',
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
