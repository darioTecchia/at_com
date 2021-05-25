<?php

class CartController extends CartControllerCore
{
  public function init()
  {
    $default_currency = intval(Configuration::get('PS_DEFAULT_CURRENCY'));
    $this->currency = new Currency($default_currency);
    $this->context->cookie->id_currency = $default_currency;
    
    parent::init();
  }
}