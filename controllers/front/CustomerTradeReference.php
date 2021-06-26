<?php

require_once _PS_MODULE_DIR_ . 'at_com_module/classes/CustomerTradeReference.php';

use At_com\CustomerTradeReferenceCore as CustomerTradeReference;

class At_com_moduleCustomerTradeReferenceModuleFrontController extends ModuleFrontController
{
    private $customerTradeReference;
    
    public function initContent()
    {
        $this->customerTradeReference = CustomerTradeReference::getByCustomerId($this->context->customer->id);
        $this->customerTradeReference = new CustomerTradeReference($this->customerTradeReference['id_customer_trade_reference']);

        $this->page_name = 'Customer Trade Reference'; // page_name and body id
        parent::initContent();

        $this->context->smarty->assign(array(
            'customerTradeReference' => $this->customerTradeReference
        ));
        $this->context->smarty->tpl_vars['page']->value['body_classes']['page-customer-account'] = true;

        $this->setTemplate('module:at_com_module/views/templates/front/customerTradeReference.tpl');
    }

    public function postProcess() {
        if(Tools::issubmit('submitCreate')) {
            $customerTradeReference = CustomerTradeReference::getByCustomerId($this->context->customer->id);
            $customerTradeReference = new CustomerTradeReference($customerTradeReference['id_customer_trade_reference']);

            $customerTradeReference->id_customer = $this->context->customer->id;
            $customerTradeReference->name = Tools::getValue("name");
            $customerTradeReference->email = Tools::getValue("email");
            $customerTradeReference->phone = Tools::getValue("phone");
            $customerTradeReference->phone_mobile = Tools::getValue("phone_mobile");
            $customerTradeReference->buyer_group = Tools::getValue("buyer_group");

            if($customerTradeReference->save()) {
                Tools::redirect(Tools::getValue("back"));
            }
        }
    } 
}
