<?php
/**
* 2007-2020 PrestaShop
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
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Whatsappbussines extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'whatsappbussines';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Christian Herrero';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('WhatssApp Chat');
        $this->description = $this->l('Add a button to your website to let the users contact you by WhatsApp.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('WHATSAPPBUSSINES_INSTALLATION', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('WHATSAPPBUSSINES_INSTALLATION');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitWhatsappbussinesModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $this->renderForm();
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
        $helper->submit_action = 'submitWhatsappbussinesModule';
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
                        'label' => $this->l('Status'),
                        'desc' => $this->l('Enable or disable the WhatsApp Button.'),
                        'name' => 'status',
                        'required' => true,
                        'values' => array(
                            array(
                                'id' => 'status_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'status_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Phone number'),
                        'name' => 'phone_number',
                        'desc' => $this->l('This is the number where the user will contact. Ej. +34999666333'),
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Call to action'),
                        'name' => 'call_to_action',
                        'desc' => $this->l('This is the text that will appear first.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Input Placeholder'),
                        'name' => 'input_placeholder',
                        'desc' => $this->l('This is the text that will appear in the input section.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Submit Text'),
                        'name' => 'submit_text',
                        'desc' => $this->l('Text for the submit button.'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Element postion'),
                        'name' => 'element_position',
                        'desc' => $this->l('Select where to display the element on the website.'),
                        'required' => true,
                        'values' => array(
                            array(
                                'id' => 'top-left',
                                'label' => 'top-left',
                                'value' => 'top-left'
                            ),
                            array(
                                'id' => 'top-right',
                                'label' => 'top-right',
                                'value' => 'top-right'
                            ),
                            array(
                                'id' => 'bottom-right',
                                'label' => 'bottom-right',
                                'value' => 'bottom-right'
                            ),
                            array(
                                'id' => 'bottom-left',
                                'label' => 'bottom-left',
                                'value' => 'bottom-left'
                            ),
                        )
                    )
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
        // Default values
        $form_values = array(
            'status' => '0',
            'phone_number' => $this->l('+34999999999'),
            'call_to_action' => $this->l('Get in touch!'),
            'input_placeholder' => $this->l('Hi, I would like to...'),
            'submit_text' => $this->l('Send'),
            'element_position' => 'top-left'
        );

        if (Configuration::get('phone_number')) {
            $form_values = array(
                'status' => Configuration::get('status'),
                'phone_number' => Configuration::get('phone_number'),
                'call_to_action' => Configuration::get('call_to_action'),
                'input_placeholder' => Configuration::get('input_placeholder'),
                'submit_text' => Configuration::get('submit_text'),
                'element_position' => Configuration::get('element_position')
            );
        }
        return $form_values;
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
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */

    public function hookDisplayHeader($params)
    {
        if (Configuration::get('status') === '1') {
            $this->context->smarty->assign([
                'number_phone' => Configuration::get('phone_number'),
                'text_cta' => Configuration::get('call_to_action'),
                'text_placeholder' => Configuration::get('input_placeholder'),
                'text_button' => Configuration::get('submit_text'),
                'position' => Configuration::get('element_position')
            ]);
            
            $this->context->controller->addJS($this->_path.'/views/js/front.js');
            $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    
            return $this->display(__FILE__, '/views/templates/hook/whatsapp.tpl');
        }
    }
}
