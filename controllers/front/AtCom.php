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

        $this->ajaxDie(Tools::jsonEncode(array(
            'cart_volume' => $cart->getCartVolume(),
            'cart_volume_100' => $cart->getCartVolume() / 100,
            'cart_pallets' => $cart->getCartPallets(),
        )));
    }
}
