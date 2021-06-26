<?php

require_once _PS_MODULE_DIR_ . 'at_com_module/classes/CustomerBank.php';

use At_com\CustomerBankCore as CustomerBank;

class At_com_moduleCustomerBankModuleFrontController extends ModuleFrontController
{
    private $customerBank;
    
    public function initContent()
    {
        $this->customerBank = CustomerBank::getByCustomerId($this->context->customer->id);
        $this->customerBank = new CustomerBank($this->customerBank['id_customer_bank']);

        $this->page_name = 'Customer Bank'; // page_name and body id
        parent::initContent();

        $this->context->smarty->assign(array(
            'customerBank' => $this->customerBank
        ));
        $this->context->smarty->tpl_vars['page']->value['body_classes']['page-customer-account'] = true;

        $this->setTemplate('module:at_com_module/views/templates/front/customerBank.tpl');
    }

    public function postProcess() {
        if(Tools::issubmit('submitCreate')) {
            $customerBank = CustomerBank::getByCustomerId($this->context->customer->id);
            $customerBank = new CustomerBank($customerBank['id_customer_bank']);

            $customerBank->id_customer = $this->context->customer->id;
            $customerBank->name = Tools::getValue("name");
            $customerBank->address = Tools::getValue("address");
            $customerBank->iban = Tools::getValue("iban");
            $customerBank->swift = Tools::getValue("swift");

            if($customerBank->save()) {
                Tools::redirect(Tools::getValue("back"));
            }
        }
    } 
}
