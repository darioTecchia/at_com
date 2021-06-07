<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
require_once _PS_MODULE_DIR_ . 'at_com_module/classes/CustomerApplication.php';
require_once _PS_MODULE_DIR_ . 'at_com_module/classes/CustomerBank.php';
require_once _PS_MODULE_DIR_ . 'at_com_module/classes/CustomerTradeReference.php';

use At_com\CustomerApplicationCore as CustomerApplication;
use At_com\CustomerBankCore as CustomerBank;
use At_com\CustomerTradeReferenceCore as CustomerTradeReference;

class AuthController extends AuthControllerCore
{
    public function initContent()
    {
        $should_redirect = false;

        if (Tools::isSubmit('submitCreate') || Tools::isSubmit('create_account')) {
            $register_form = $this
                ->makeCustomerForm()
                ->setGuestAllowed(false)
                ->fillWith(Tools::getAllValues());

            if (Tools::isSubmit('submitCreate')) {
                $hookResult = array_reduce(
                    Hook::exec('actionSubmitAccountBefore', [], null, true),
                    function ($carry, $item) {
                        return $carry && $item;
                    },
                    true
                );

                if ($hookResult && $register_form->submit()) {

                    //NEED TO CREATE HERE NEW CUSTOMER APPLIACE AND BANK INFOS
                    $customerApplication = new CustomerApplication();
                    $customerApplication->id_customer = $register_form->getCustomer()->id;
                    $customerApplication->brands = Tools::getValue('brands');
                    $customerApplication->b2b = Tools::getValue('tc_b2b');
                    $customerApplication->b2c = Tools::getValue('tc_b2c');
                    $customerApplication->website = Tools::getValue('website');
                    $customerApplication->amazon = Tools::getValue('tc_amazon');
                    $customerApplication->ebay = Tools::getValue('tc_ebay');
                    $customerApplication->other = Tools::getValue('tc_other');

                    $customerBank = new CustomerBank();
                    $customerBank->id_customer = $register_form->getCustomer()->id;
                    $customerBank->name = Tools::getValue('bank_name');
                    $customerBank->address = Tools::getValue('bank_address');
                    $customerBank->iban = Tools::getValue('iban');
                    $customerBank->swift = Tools::getValue('swift');

                    $customerTradeReference = new CustomerTradeReference();
                    $customerTradeReference->id_customer = $register_form->getCustomer()->id;
                    $customerTradeReference->name = Tools::getValue('tr_name');
                    $customerTradeReference->email = Tools::getValue('tr_email');
                    $customerTradeReference->phone = Tools::getValue('tr_phone');
                    $customerTradeReference->phone_mobile = Tools::getValue('tr_cell');
                    $customerTradeReference->buyer_group = Tools::getValue('tr_group');

                    if ($customerApplication->save() && $customerBank->save() && $customerTradeReference->save()) {
                        $should_redirect = true;
                    } else {
                        $register_form->getCustomer()->delete();
                        $this->errors[] = $this->trans('Could not update your information, please check your data.', array(), 'Shop.Notifications.Error');
                        $this->redirectWithNotifications($this->getCurrentURL());
                    }
                    // $should_redirect = true;
                }
            }

            $this->context->smarty->assign([
                'register_form' => $register_form->getProxy(),
                'hook_create_account_top' => Hook::exec('displayCustomerAccountFormTop'),
            ]);
            $this->setTemplate('customer/registration');
        } else {
            $login_form = $this->makeLoginForm()->fillWith(
                Tools::getAllValues()
            );

            if (Tools::isSubmit('submitLogin')) {
                if ($login_form->submit()) {
                    $should_redirect = true;
                }
            }

            $this->context->smarty->assign([
                'login_form' => $login_form->getProxy(),
            ]);
            $this->setTemplate('customer/authentication');
        }

        parent::initContent();

        if ($should_redirect && !$this->ajax) {
            $back = rawurldecode(Tools::getValue('back'));

            if (Tools::urlBelongsToShop($back)) {
                // Checks to see if "back" is a fully qualified
                // URL that is on OUR domain, with the right protocol
                return $this->redirectWithNotifications($back);
            }

            // Well we're not redirecting to a URL,
            // so...
            if ($this->authRedirection) {
                // We may need to go there if defined
                return $this->redirectWithNotifications($this->authRedirection);
            }

            // go home
            return $this->redirectWithNotifications(__PS_BASE_URI__);
        }
    }
}
