<?php

class At_com_moduleAtComModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initContent()
    {
        $this->ajax = true;
        parent::initContent();
    }

    public function displayAjaxCartPallet()
    {

        $context = Context::getContext();
        $cart = $context->cart;
        
        $this->context->smarty->assign([
            'cart_volume' => $cart->getCartVolume(),
            'cart_pallets' => $cart->getCartPallets(),
            'pallet_capiency' => (int) Configuration::get('AT_COM_MODULE_PALLET_CAP', 20),
        ]);
        
        ob_end_clean();
        header('Content-Type: application/json');
        $this->ajaxRender(Tools::jsonEncode([
            'cart_pallet' => $this->display(dirname(__FILE__), '/views/templates/front/cartFooter.tpl')
        ]));
    }
}
