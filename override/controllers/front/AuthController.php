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
                    // customer application
                    $customerApplication = new CustomerApplication();
                    $customerApplication->id_customer = $register_form->getCustomer()->id;
                    $customerApplication->brands = Tools::getValue('brands');
                    $customerApplication->b2b = Tools::getValue('tc_b2b');
                    $customerApplication->b2c = Tools::getValue('tc_b2c');
                    $customerApplication->website = Tools::getValue('website');
                    $customerApplication->attachment = Tools::getValue('attachment');
                    $customerApplication->amazon = Tools::getValue('tc_amazon');
                    $customerApplication->ebay = Tools::getValue('tc_ebay');
                    $customerApplication->other = Tools::getValue('tc_other');

                    $fileName = "";
                    $fileName .= uniqid();
                    $fileName .= '.';
                    $fileName .= pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
                    $fileName = strtolower($fileName);
                    $fileName = filter_var($fileName, FILTER_SANITIZE_STRING);
                    $_FILES['attachment']['name'] = $fileName;

                    $uploader = new Uploader();
                    $uploader->upload($_FILES['attachment']);
                    $customerApplication->attachment = $fileName;

                    // customer bank
                    $customerBank = new CustomerBank();
                    $customerBank->id_customer = $register_form->getCustomer()->id;
                    $customerBank->name = Tools::getValue('bank_name');
                    $customerBank->address = Tools::getValue('bank_address');
                    $customerBank->iban = Tools::getValue('iban');
                    $customerBank->swift = Tools::getValue('swift');

                    // customer trade reference
                    $customerTradeReference = new CustomerTradeReference();
                    $customerTradeReference->id_customer = $register_form->getCustomer()->id;
                    $customerTradeReference->name = Tools::getValue('tr_name');
                    $customerTradeReference->email = Tools::getValue('tr_email');
                    $customerTradeReference->phone = Tools::getValue('tr_phone');
                    $customerTradeReference->phone_mobile = Tools::getValue('tr_cell');
                    $customerTradeReference->buyer_group = Tools::getValue('tr_group');

                    // legal address
                    $legalAddress = new Address(
                        null,
                        $this->context->language->id
                    );
                    $legalAddress->id_country = (int) Tools::getValue("id_country");
                    $legalAddress->id_state = (int) Tools::getValue("id_state");
                    $legalAddress->address1 = Tools::getValue('address1');
                    $legalAddress->address2 = Tools::getValue('address2');
                    $legalAddress->postcode = Tools::getValue('id_country_postcode');
                    $legalAddress->city = Tools::getValue('city');
                    $legalAddress->phone = Tools::getValue('phone');
                    $legalAddress->dni = Tools::getValue('dni');
                    
                    $legalAddress->firstname = $register_form->getCustomer()->firstname;
                    $legalAddress->lastname = $register_form->getCustomer()->lastname;
                    $legalAddress->id_customer = $register_form->getCustomer()->id;
                    
                    $legalAddress->alias = $this->trans('Sede Legale', [], 'Shop.Theme.Checkout');

                    // operative address
                    $operativeAddress = new Address(
                        null,
                        $this->context->language->id
                    );
                    $operativeAddress->id_country = (int) Tools::getValue("op_id_country");
                    $operativeAddress->id_state = (int) Tools::getValue("op_id_state");
                    $operativeAddress->address1 = Tools::getValue('op_address1');
                    $operativeAddress->address2 = Tools::getValue('op_address2');
                    $operativeAddress->postcode = Tools::getValue('op_id_country_postcode');
                    $operativeAddress->city = Tools::getValue('op_city');
                    $operativeAddress->phone = Tools::getValue('op_phone');
                    $operativeAddress->dni = Tools::getValue('dni');
                    
                    $operativeAddress->firstname = $register_form->getCustomer()->firstname;
                    $operativeAddress->lastname = $register_form->getCustomer()->lastname;
                    $operativeAddress->id_customer = $register_form->getCustomer()->id;
                    
                    $operativeAddress->alias = $this->trans('Sede Operativa', [], 'Shop.Theme.Checkout');

                    $successfull = $customerApplication->save() 
                        && $customerBank->save() 
                        && $customerTradeReference->save()
                        && $legalAddress->save()
                        && $operativeAddress->save();

                    if ($successfull) {
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
