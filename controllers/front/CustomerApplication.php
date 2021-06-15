<?php

class At_com_moduleCustomerApplicationModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->page_name = 'Customer Application'; // page_name and body id
        parent::initContent();

        $this->context->smarty->assign(array(
            'hello' => 'Hello World!!!',
        ));
        $this->context->smarty->tpl_vars['page']->value['body_classes']['page-customer-account'] = true;

        $this->setTemplate('module:at_com_module/views/templates/front/customerApplication.tpl');
    }

    public function postProcess() {
        if((int)Tools::getValue("submitCreate") == 1) {
            dump(Tools::getValue("submitCreate"));
        }
    } 
}
