{**
 * 2007-2020 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

 <script>
  var cart_pallet_url= '{url entity='module' name='at_com_module' controller='AtCom' params = [action => 'CartPallet']}';
</script>

<div class="card mb-0 mt-1" id="at_com_module-cart">
    <div class="card-block">
        <h1 class="h1">{l s='Pallets Infos' mod='at_com_module'}</h1>
    </div>
    <hr class="separator">
    <div class="card-block">
        • {l s='Total volume of the shipping' mod='at_com_module'} in cm<sup>3</sup>: <b><span id="cart_volume">{$cart_volume}</span> cm<sup>3</sup></b>
        <br>
        • {l s='Total volume of the shipping' mod='at_com_module'} in m<sup>3</sup>: <b><span id="cart_volume_100">{$cart_volume / 100}</span> m<sup>3</sup></b>
        <br>
        • {l s='Single pallet capiency' mod='at_com_module'} in m<sup>3</sup>: <b><span id="pallet_capiency">{$pallet_capiency}</span> m<sup>3</sup></b>
        <br>
        • {l s='Aproximative number of pallet' mod='at_com_module'}: <b><span id="cart_pallets">{$cart_pallets}</span></b>
    </div>
</div>
