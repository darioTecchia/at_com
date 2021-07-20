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
            'id_customer_application' => ['title' => $this->l('ID'), 'type' => 'number'],
            'id_customer' => ['title' => $this->l('Customer'), 'type' => 'number'],
            'pec' => ['title' => $this->l('PEC'), 'type' => 'text'],
            'brands' => ['title' => $this->l('Brands'), 'type' => 'text'],
            'b2b' => ['title' => $this->l('B2B'), 'type' => 'bool'],
            'b2c' => ['title' => $this->l('B2C'), 'type' => 'bool'],
            'attachment' => ['title' => $this->l('Attachment'), 'type' => 'file'],
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
                    'type' => 'hidden',
                    'label' => $this->l('ID Customer'),
                    'name' => 'id_customer',
                    'required' => true,
                ],
                'pec' => [
                    'type' => 'text',
                    'label' => $this->l('PEC'),
                    'name' => 'pec',
                    'required' => true,
                ],
                'brands' => [
                    'type' => 'text',
                    'label' => $this->l('Brands'),
                    'name' => 'brands',
                    'required' => true,
                ],
                'b2b' => [
                    'type' => 'switch',
                    'label' => $this->l('B2B'),
                    'name' => 'b2b',
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
                    'type' => 'switch',
                    'label' => $this->l('B2C'),
                    'name' => 'b2c',
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
                'attachment' => [
                    'type' => 'file',
                    'label' => $this->l('Attachment'),
                    'name' => 'attachment'
                ],
                'website' => [
                    'type' => 'text',
                    'label' => $this->l('Website'),
                    'name' => 'website',
                ],
                'amazon' => [
                    'type' => 'text',
                    'label' => $this->l('Amazon'),
                    'name' => 'amazon',
                ],
                'ebay' => [
                    'type' => 'text',
                    'label' => $this->l('Ebay'),
                    'name' => 'ebay',
                ],
                'other' => [
                    'type' => 'text',
                    'label' => $this->l('Other'),
                    'name' => 'other',
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];
    }

    public function postProcess()
    {
        $customerApplication = new At_com\CustomerApplicationCore($this->id_object);
        if((bool) Tools::isSubmit('submitAddcustomer_application') == true) {
            if($_FILES['attachment']['name']) {
                    
                if($customerApplication->attachment != "") {
                    $fileName = $customerApplication->attachment;
                } else {
                    $fileName = "";
                    $fileName .= uniqid();
                    $fileName .= '.';
                    $fileName .= pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
                    $fileName = strtolower($fileName);
                    $fileName = filter_var($fileName, FILTER_SANITIZE_STRING);
                }
                $_FILES['attachment']['name'] = $fileName;
    
                $uploader = new Uploader();
                $uploader->upload($_FILES['attachment']);
                $customerApplication->attachment = $fileName;
                $_POST['attachment'] = $fileName;
            }
        }
        parent::postProcess();
    }

    public function initContent()
    {
        parent::initContent();
    }
}
