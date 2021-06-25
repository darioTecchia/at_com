<?php

/**
 * Class Cart
 */
class Cart extends CartCore
{
    /**
     * Cart constructor.
     *
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    private function volumeReducer($carry, $item)
    {
        $carry += ( (double) $item["width"] * (double) $item["height"] * (double) $item["depth"]) * (int) $item["cart_quantity"];
        return $carry;
    }

    public function getCartVolume()
    {
        return array_reduce(parent::getProducts(), array("Cart", "volumeReducer"));
    }

    public function getCartPallets($palletCapacity = null)
    {
        if(is_null($palletCapacity)) {
            $palletCapacity = (int) Configuration::get('AT_COM_MODULE_PALLET_CAP', 20);
        }
        return (int) ceil(($this->getCartVolume() / 100) / $palletCapacity);
    }

}
