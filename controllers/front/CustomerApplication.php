<?php

require_once _PS_MODULE_DIR_ . 'at_com_module/classes/CustomerApplication.php';

use At_com\CustomerApplicationCore as CustomerApplication;

class At_com_moduleCustomerApplicationModuleFrontController extends ModuleFrontController
{
    private $customerApplication;
    
    public function initContent()
    {
        $this->customerApplication = CustomerApplication::getByCustomerId($this->context->customer->id);
        $this->customerApplication = new CustomerApplication($this->customerApplication['id_customer_application']);

        $this->page_name = 'Customer Application'; // page_name and body id
        parent::initContent();

        $this->context->smarty->assign(array(
            'customerApplication' => $this->customerApplication
        ));
        $this->context->smarty->tpl_vars['page']->value['body_classes']['page-customer-account'] = true;

        $this->setTemplate('module:at_com_module/views/templates/front/customerApplication.tpl');
    }

    public function postProcess() {
        if((bool) Tools::isSubmit('submitCreate') == true) {
            $customerApplication = CustomerApplication::getByCustomerId($this->context->customer->id);
            $customerApplication = new CustomerApplication($customerApplication['id_customer_application']);

            $customerApplication->id_customer = $this->context->customer->id;
            $customerApplication->brands = Tools::getValue("brands");
            $customerApplication->b2b = Tools::getValue("b2b");
            $customerApplication->b2c = Tools::getValue("b2c");
            $customerApplication->website = Tools::getValue("website");
            $customerApplication->amazon = Tools::getValue("amazon");
            $customerApplication->ebay = Tools::getValue("ebay");
            $customerApplication->other = Tools::getValue("other");

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
            }

            if($customerApplication->save()) {
                Tools::redirect(Tools::getValue("back"));
            }
        }
    } 
}
