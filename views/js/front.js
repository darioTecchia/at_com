/**
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
// http://localhost/shop-admin/index.php?controller=AdminModules&module_name=at_com_module&token=91d19d8f611d6b94e6c895065ce48451
if (typeof prestashop !== 'undefined') {
  prestashop.on(
    'updatedCart',
    function (event) {
      console.log(this.cart);
      console.log(event);


      var token = prestashop.token;
      var actionURL = '/shop-admin/index.php';
      var query = 'controller=AdminModules&module_name=at_com_module&submitCartUpdate=1&ajax=true&token=' + token;
      $.get(actionURL, query, null, 'json').then(function (resp) {
        console.log(resp);
      }).fail(function (resp) {
        prestashop.emit('handleError', { eventType: 'updateCart', resp: resp });
      });
    }
  );
}