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
require_once _PS_MODULE_DIR_ . 'at_com_module/classes/ModuleCronsManager.php';

use At_com\CustomerApplicationCore as CustomerApplication;
use At_com\CustomerBankCore as CustomerBank;
use At_com\CustomerTradeReferenceCore as CustomerTradeReference;
use At_com\ModuleCronsManager as ModuleCronsManager;
use PrestaShopBundle\Form\Admin\Type\DatePickerType;

class At_com_module extends Module
{

    protected $config_form = false;

    private $crons_manager;

    public function __construct()
    {
        $this->name = 'at_com_module';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'dariotecchia';
        $this->need_instance = 1;

        $this->hooks = array(
            'header',
            'backOfficeHeader',
            'displayShoppingCartFooter',
            'displayCustomerAdditionalInfoTop',
            'displayCustomerAdditionalInfoBottom',
            'displayAdminOrderSide',
            'actionCustomerFormBuilderModifier',
            'actionAfterCreateCustomerFormHandler',
            'actionAfterUpdateCustomerFormHandler',
            'actionObjectCustomerDeleteBefore',
            'displayAdminCustomers',
            'displayCustomerAccount',
        );

        $this->controllers = array(
            "AdminCustomerApplicationController",
            "AdminCustomerBankController",
            "AdminCustomerTradeReferenceController",
        );

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('@.com module');
        $this->description = $this->l('Prestashop Module developed for @.com.');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->crons_manager = new ModuleCronsManager($this->_path);
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
        Configuration::updateValue('PS_B2B_ENABLE', 1);

        Feature::addFeatureImport("Amazon SKU");
        Feature::addFeatureImport("Ebay SKU");

        return parent::install() &&
        $this->installTab() &&
        $this->registerHooks();
    }

    public function uninstall()
    {
        include dirname(__FILE__) . '/sql/uninstall.php';

        return $this->unregisterHook('header') &&
        $this->unregisterHooks() &&
        $this->uninstallTab() &&
        parent::uninstall();
    }

    private function registerHooks()
    {
        $successfull = true;

        foreach ($this->hooks as $hook) {
            $successfull &= $this->registerHook($hook);
        }

        return $successfull;
    }

    private function unregisterHooks()
    {
        $successfull = true;
        foreach ($this->hooks as $hook) {
            $successfull &= $this->unregisterHook($hook);
        }
        return $successfull;
    }

    public function installTab()
    {
        $successfull = true;

        foreach ($this->controllers as $key => $controller) {
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
        $successfull = true;

        foreach ($this->controllers as $key => $controller) {

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

        $cronJobsModule = Module::getInstanceByName('cronjobs');

        $this->context->smarty->assign('cronJobsModule', $cronJobsModule);
        $this->context->smarty->assign('exsistCustomerCron', $this->crons_manager->exsistCron("cron_expired_customers.php"));

        $actionUrl = $this->context->link->getAdminLink("AdminModules") . "&configure=at_com_module&tab_module=administration&module_name=at_com_module";

        $this->context->smarty->assign('actionUrl', $actionUrl);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

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
        } else if (((bool) Tools::isSubmit('submitAt_com_moduleCreateCron')) == true) {
            if ((((bool) Tools::isSubmit('create_product_cron')) == true)) {
            } elseif ((((bool) Tools::isSubmit('create_customer_cron')) == true)) {
                if ($this->crons_manager->createCronJob("cron_expired_customers.php")) {
                    $redirect_link = $this->context->link->getAdminLink('AdminModules', false)
                    . '?configure=cronjobs&token=' . Tools::getAdminTokenLite('AdminModules');
                    Tools::redirectLink($redirect_link);
                }
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

    public function hookDisplayCustomerAdditionalInfoTop($params)
    {
        $customer = new Customer($params['id_customer']);

        $infos = "";
        $infos .= $this->render($this->getModuleTemplatePath() . 'topAdditionalCustomerInfos.html.twig', [
            'customer' => $customer,
        ]);
        return $infos;
    }

    public function hookDisplayCustomerAdditionalInfoBottom($params)
    {
        $customer = new Customer($params['id_customer']);
        $customer->exp_date = $lastUpdateDate = Tools::displayDate($customer->exp_date, null, true);

        $infos = "";
        $infos .= $this->render($this->getModuleTemplatePath() . 'bottomAdditionalCustomerInfos.html.twig', [
            'customer' => $customer,
        ]);
        return $infos;
    }

    public function hookActionCustomerFormBuilderModifier(array $params)
    {
        $formBuilder = $params['form_builder'];
        $formBuilder->add('exp_date', DatePickerType::class, [
            'label' => 'Deactivation date',
            'required' => false,
        ]);

        $customerId = $params['id'];
        $customer = new Customer($customerId);

        $params['data']['exp_date'] = $customer->exp_date;

        $formBuilder->setData($params['data']);
    }

    public function hookActionAfterUpdateCustomerFormHandler(array $params)
    {
        $customerId = $params['id'];
        $customer = new Customer($customerId);
        $customer->exp_date = $params['form_data']['exp_date'];
        $customer->update();
    }

    public function hookActionAfterCreateCustomerFormHandler(array $params)
    {
        $customerId = $params['id'];
        $customer = new Customer($customerId);
        $customer->exp_date = $params['form_data']['exp_date'];
        $customer->update();
    }

    public function hookActionObjectCustomerDeleteBefore(array $params)
    {
        $successfull = true;
        $customer = $params['object'];
        $customerApplication = CustomerApplication::getByCustomerId($customer->id);
        if ($customerApplication) {
            $customerApplication = new CustomerApplication($customerApplication['id_customer_application']);
            $successfull &= $customerApplication->delete();
        }
        $customerBank = CustomerBank::getByCustomerId($customer->id);
        if ($customerBank) {
            $customerBank = new CustomerBank($customerBank['id_customer_bank']);
            $successfull &= $customerBank->delete();
        }
        $customerTradeReference = CustomerTradeReference::getByCustomerId($customer->id);
        if ($customerTradeReference) {
            $customerTradeReference = new CustomerTradeReference($customerTradeReference['id_customer_trade_reference']);
            $successfull &= $customerTradeReference->delete();
        }
        return $successfull;
    }

    public function hookDisplayAdminOrderSide(array $params)
    {
        $context = Context::getContext();
        $order = new Order($params['id_order']);
        $cart = new Cart($order->id_cart);
        dump($order);
        $this->context->smarty->assign([
            'cart_volume' => $cart->getCartVolume(),
            'cart_pallets' => $cart->getCartPallets(),
            'pallet_capiency' => (int) Configuration::get('AT_COM_MODULE_PALLET_CAP', 20),
        ]);
        return $this->display(dirname(__FILE__), '/views/templates/admin/palletInfos.tpl');
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
