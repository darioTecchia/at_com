<?php
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
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'at_com_module/classes/CustomerApplication.php';
require_once _PS_MODULE_DIR_ . 'at_com_module/classes/CustomerBank.php';
require_once _PS_MODULE_DIR_ . 'at_com_module/classes/CustomerTradeReference.php';
require_once _PS_MODULE_DIR_ . 'at_com_module/classes/PaymentMethodRule.php';

use At_com\CustomerApplicationCore as CustomerApplication;
use At_com\CustomerBankCore as CustomerBank;
use At_com\CustomerTradeReferenceCore as CustomerTradeReference;
use At_com\PaymentMethodRuleCore as PaymentMethodRule;

class At_com_module extends Module
{

    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'at_com_module';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'dariotecchia';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('@.com module');
        $this->description = $this->l('Prestashop Module developed for @.com.');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include dirname(__FILE__) . '/sql/install.php';

        Configuration::updateValue('PS_WEBSERVICE', 1);

        $apiAccess = new WebserviceKey();
        $apiAccess->key = '4WMDKYTLS146K63ZA9CFIAN7VGJE4L9V';
        $apiAccess->description = 'Chiave per ottenere gli stati durante la fase di registrazione.';
        $apiAccess->save();

        $permissions = [
            'countries' => ['GET' => 1],
            'states' => ['GET' => 1],
            'zones' => ['GET' => 1],
        ];
          
        WebserviceKey::setPermissionForAccount($apiAccess->id, $permissions);

        Configuration::updateValue('ATCOM_STATES_KEY', base64_encode($apiAccess->key . ':'));

        Feature::addFeatureImport("Amazon SKU");
        Feature::addFeatureImport("Ebay SKU");

        return parent::install() &&
        $this->installTab() &&
        $this->registerHook('header') &&
        $this->registerHook('backOfficeHeader') &&
        $this->registerHook('displayShoppingCartFooter') &&
        $this->registerHook('displayAdminCustomers') &&
        $this->registerHook('displayCustomerAccount');
    }

    public function uninstall()
    {
        include dirname(__FILE__) . '/sql/uninstall.php';

        return $this->unregisterHook('header') &&
        $this->unregisterHook('backOfficeHeader') &&
        $this->unregisterHook('displayShoppingCartFooter') &&
        $this->unregisterHook('displayAdminCustomers') &&
        $this->unregisterHook('displayCustomerAccount') &&
        $this->uninstallTab() &&
        parent::uninstall();
    }

    public function installTab()
    {
        $controllers = array("AdminCustomerApplicationController", "AdminCustomerBankController", "AdminCustomerTradeReferenceController");
        $successfull = true;

        foreach ($controllers as $key => $controller) {
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = $controller;

            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = $controller;
            }

            $tab->id_parent = -1;
            $tab->module = $this->name;

            $successfull = $successfull && $tab->add();
        }
        return $successfull;
    }

    public function uninstallTab()
    {
        $controllers = array("AdminCustomerApplicationController", "AdminCustomerBankController", "AdminCustomerTradeReferenceController");
        $successfull = true;

        foreach ($controllers as $key => $controller) {

            $id_tab = (int) Tab::getIdFromClassName($controller);

            if ($id_tab) {
                $tab = new Tab($id_tab);
                $successfull = $successfull && $tab->delete();
            }
        }
        return $successfull;
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        $this->postProcess();

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        $payment_methods = array();
        foreach (PaymentModule::getInstalledPaymentModules() as $payment) {
            $module = Module::getInstanceByName($payment['name']);
            if (Validate::isLoadedObject($module) && $module->active) {
                $payment_methods[] = $module;
            }
        }

        $payment_methods_rules = PaymentMethodRule::getPaymentMethodRules();
        $map = array();
        foreach ($payment_methods_rules as $payment_methods_rule) {
            $map[$payment_methods_rule['id_payment_method']] = $payment_methods_rule;
        }
        $payment_methods_rules = $map;

        foreach ($payment_methods as $key => $payment_method) {
            if(isset($payment_methods_rules[$payment_method->id])) {
                $payment_methods[$key]->rule_active = (bool) $payment_methods_rules[$payment_method->id]['active'];
                $payment_methods[$key]->rule = (double) $payment_methods_rules[$payment_method->id]['rule'];
            }
        }

        $this->context->smarty->assign('payment_methods', $payment_methods);

        $output .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure_payments.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitAt_com_moduleModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
        . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $form = $helper->generateForm(array($this->getConfigForm()));

        return $form;
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        $form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Pallet Settings'),
                    'icon' => 'icon-AdminParentShipping',
                ),
                'input' => array(
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'suffix' => 'm³',
                        'desc' => $this->l('Inserisci la capienza massima del singolo pallet in m³'),
                        'name' => 'AT_COM_MODULE_PALLET_CAP',
                        'label' => $this->l('Capienza Pallet'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
        return $form;
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'AT_COM_MODULE_PALLET_CAP' => Configuration::get('AT_COM_MODULE_PALLET_CAP', 20),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        if (((bool) Tools::isSubmit('submitAt_com_moduleModule')) == true) {
            $form_values = $this->getConfigFormValues();

            foreach (array_keys($form_values) as $key) {
                Configuration::updateValue($key, Tools::getValue($key));
            }
        } else if (((bool) Tools::isSubmit('submitAt_com_modulePaymentRule')) == true) {
            $form = Tools::getValue('form');
            foreach ($form['price_rules'] as $payment_method_id => $payment_method_rule) {
                $PaymentMethodRule = PaymentMethodRule::getByPaymentMethodId($payment_method_id);
                $PaymentMethodRule->rule = $form['price_rules'][$payment_method_id]['rule'];
                $PaymentMethodRule->active = isset($form['price_rules'][$payment_method_id]['active']);
                $PaymentMethodRule->save();
            }
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $context = $this->context;
        if ($context->controller->php_self == 'authentication') {
            $this->context->controller->registerJavascript(
                'registration-module',
                $this->_path . '/views/js/registration.js'
            );
            // $this->context->controller->addJS($this->_path . '/views/js/registration.js');
        }
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    public function hookDisplayAdminCustomers($params)
    {
        $customer = new Customer($params['id_customer']);
        $customerApplication = CustomerApplication::getByCustomerId($params['id_customer']);
        $customerBank = CustomerBank::getByCustomerId($params['id_customer']);
        $customerTradeReference = CustomerTradeReference::getByCustomerId($params['id_customer']);

        $sections = "";
        $sections .= $this->render($this->getModuleTemplatePath() . 'customer_application_info.html.twig', [
            'customer' => $customer,
            'customerApplication' => $customerApplication,
        ]);

        $sections .= $this->render($this->getModuleTemplatePath() . 'customer_bank_info.html.twig', [
            'customer' => $customer,
            'customerBank' => $customerBank,
        ]);

        $sections .= $this->render($this->getModuleTemplatePath() . 'customer_trade_reference_info.html.twig', [
            'customer' => $customer,
            'customerTradeReference' => $customerTradeReference,
        ]);

        return $sections;
    }

    /**
     * @return string
     */
    public function hookDisplayCustomerAccount()
    {
        $context = Context::getContext();
        $id_customer = $context->customer->id;

        $boxes = "";

        $customerBoxes = array(
            [
                'front_controller' => 'CustomerApplication',
                'label' => 'Customer Application',
                'icon' => 'business',
            ],
            [
                'front_controller' => 'CustomerBank',
                'label' => 'Customer Bank',
                'icon' => 'account_balance',
            ],
            [
                'front_controller' => 'CustomerTradeReference',
                'label' => 'Customer Trade Reference',
                'icon' => 'account_balance_wallet',
            ],
        );

        foreach ($customerBoxes as $customerBox) {
            $url = $context->link->getModuleLink(
                $this->name,
                $customerBox['front_controller'],
                [],
                Configuration::get('PS_SSL_ENABLED')
            );
            $this->context->smarty->assign([
                'front_controller_url' => $url . '?back=' . urlencode($url),
                'label' => $customerBox['label'],
                'icon' => $customerBox['icon'],
            ]);
            $boxes .= $this->display(dirname(__FILE__), '/views/templates/_partials/customerBox.tpl');
        }
        return $boxes;
    }

    public function hookDisplayShoppingCartFooter($params)
    {
        $context = Context::getContext();
        $cart = $context->cart;
        $this->context->smarty->assign([
            'cart_volume' => $cart->getCartVolume(),
            'cart_pallets' => $cart->getCartPallets(),
            'pallet_capiency' => (int) Configuration::get('AT_COM_MODULE_PALLET_CAP', 20),
        ]);
        return $this->display(dirname(__FILE__), '/views/templates/front/cartFooter.tpl');
    }

    /**
     * Render a twig template.
     */
    private function render(string $template, array $params = []): string
    {
        /** @var Twig_Environment $twig */
        $twig = PrestaShop\PrestaShop\Adapter\SymfonyContainer::getInstance()->get('twig');

        return $twig->render($template, $params);
    }

    /**
     * Get path to this module's template directory
     */
    private function getModuleTemplatePath(): string
    {
        return sprintf('@Modules/%s/views/templates/admin/', $this->name);
    }
}
