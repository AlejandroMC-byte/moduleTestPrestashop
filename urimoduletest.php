<?php
/**
* 2007-2022 PrestaShop
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
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
// $options = [];
//         foreach (Group::getGroups($this->context->language->id) as $client)
//             {
//                 $options[] = array(
//                     "id" => (int)$client->id,
//                     "name" => $client->name
//                 );
//             }
// if (!defined('_PS_VERSION_')) {
//     exit;
// }

class UriModuleTest extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'urimoduletest';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'alejoMc';
        $this->need_instance = 1;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Modulo de test');
        $this->description = $this->l('descripcion del modulo de test');
        $this->confirmUninstall = $this->l('Estas seguro que deseas desinstalar?');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {       
        return parent::install() && $this->registerHook('displayAfterProductThumbs');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {
        return $this->getForm();
    }

    public function postProcess()
    {
        if(Tools::isSubmit('cambiar_nombre')){
            $variable_mensaje = Tools::getValue('variable_mensaje');
            Configuration::updateValue('URI_MODULE_TEST_TEXTO',$variable_mensaje);
        }
    }

    private function getForm()
    {
        $helper =new HelperForm();
        $helper->module = $this;
        $helper->nano_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages =$this->context->controller->getLanguages();
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language =$this->context->controller->default_form_language;
        $helper->allow_employee_form_lang =$this->context->controller->allow_employee_form_lang;
        $helper->title =$this->displayName;

        $helper->submit_action = 'cambiar_nombre';

        $helper->fields_value = [
            'variable_mensaje' => Configuration::get('URI_MODULE_TEST_TEXTO'),
            //'clientes' =>(array) Group::getGroups($this->context->language->id),
            //'cliente' => ,
        ];

        $form[] = [
            'form' =>[
                'legend' => [
                    'title'=>$this->l('Escribe un mensaje'),
                ],
                'input' => [
                    [
                        'type'=> 'textarea',
                        'name'=> 'variable_mensaje',
                        'label'=> $this->l('Crear mensaje'),
                        'desc'=> $this->l('Maximum 200 characters'),
                    ],
                    [
                        'type'=> 'select',
                        'name'=> 'cliente',
                        'label'=> $this->l('Cliente'),
                        'required' =>true,
                        'options' => [
                            'query' => Group::getGroups($this->context->language->id),
                            'id' => 'id_option',
                            'name' => 'name',
                        ]
                        
                    ],
                ],
                'submit' => [
                    'title'=> $this->l('save'),
                ],
            ]
            ];

        return $helper->generateForm($form);
    }

    public function getClientTest()
    {
        $options = [];
        foreach (Group::getGroups($this->context->language->id) as $client)
            {
                $options[] = array(
                    "id" => (int)$client->id,
                    "name" => $client->name
                );
            }
        return $options;
    }

    public function HookDisplayAfterProductThumbs()
    {
        //return $this->context->smarty->fetch($this->local_path .'views/templates/hook/product.tpl');

        $texto =  '<span>'.$this->l('hello world').'</span>' ;
        $this->context->smarty->assign([
            'texto' => $texto,
        ]);
        return $this->display(__FILE__, 'product.tpl');
    }



}   
