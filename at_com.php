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

class at_com extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'at_com';
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
        $this->description = $this->l('Prestashop module built in house by @.com Srl.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('AT_COM_LIVE_MODE', false);

        Configuration::updateValue('PS_B2B_ENABLE', true);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
            // $this->registerHook('additionalCustomerFormFields') &&
            // $this->registerHook('validateCustomerFormFields');
    }

    public function uninstall()
    {
        Configuration::deleteByName('AT_COM_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitAt_comModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
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
        $helper->submit_action = 'submitAt_comModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'AT_COM_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'AT_COM_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'AT_COM_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'AT_COM_LIVE_MODE' => Configuration::get('AT_COM_LIVE_MODE', true),
            'AT_COM_ACCOUNT_EMAIL' => Configuration::get('AT_COM_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'AT_COM_ACCOUNT_PASSWORD' => Configuration::get('AT_COM_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    /**
     * Read Module fields values
     *
     * @return array of module value
     */
    // protected function readModuleValues()
    // {
    //     $id_customer = Context::getContext()->customer->id;
    //     $query = 'SELECT c.`sdi`'
    //         .' FROM `'. _DB_PREFIX_.'customer` c '
    //         .' WHERE c.id_customer = '.(int)$id_customer;
    //     return  Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
    // }

    /**
     * Write module fields values
     *
     * @return nothing
     */
    // protected function writeModuleValues($id_customer)
    // {
    //     $sdi = Tools::getValue('sdi');
    //     $query = 'UPDATE `'._DB_PREFIX_.'customer` c '
    //         .' SET  c.`sdi` = "'.pSQL($sdi).'"'
    //         .' WHERE c.id_customer = '.(int)$id_customer;
    //     Db::getInstance()->execute($query);
    // }

    /**
     * Add fields in Customer Form
     *
     * @param array $params parameters (@see CustomerFormatter->getFormat())
     *
     * @return array of extra FormField
     */
    // public function hookAdditionalCustomerFormFields($params)
    // {
    //     $module_fields = $this->readModuleValues();

    //     $sdi_value = Tools::getValue('sdi');
    //     if (isset($module_fields['sdi'])) {
    //         $sdi_value = $module_fields['sdi'];
    //     }

    //     $extra_fields = array();
    //     $extra_fields['sdi'] = (new FormField)
    //         ->setName('sdi')
    //         ->setType('text')
    //         ->setValue($sdi_value)
    //         ->setLabel($this->l('SDI'));

    //     return $extra_fields;
    // }

    /**
     * Validate fields in Customer form
     *
     * @param array $params hook call parameters (@see CustomerForm->validateByModules())
     *
     * @return array of extra FormField
     */
    // public function hookvalidateCustomerFormFields($params)
    // {
    //     $module_fields = $params['fields'];
    //     if ('Dance' != $module_fields[0]->getValue()
    //         && 'Shopping' != $module_fields[0]->getValue()
    //         && 'Yoga' != $module_fields[0]->getValue()
    //     ) {
    //         $module_fields[0]->addError(
    //             $this->l('Only "Dance", "Shopping" or "Yoga"')
    //         );
    //     }
    //     return array(
    //         $module_fields
    //     );
    // }
}
