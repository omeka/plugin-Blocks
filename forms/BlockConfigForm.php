<?php
/**
 * Block Config form.
 *
 * @package Blocks\Form
 */
class BlockConfigForm extends Omeka_Form_Admin
{
    protected $_type = 'BlockConfig';

    public $blockClass;
    public $routes;
    private $_blockConfig;

    public function init()
    {
        parent::init();

        $this->setAttrib('id', 'blocks-config');
        $this->setMethod('post');

        $blockClass = $this->blockClass;

        $this->addElement('hidden', 'class_name', array(
            'value' => $blockClass,
        ));

        $value = $this->_blockConfig ? $this->_blockConfig->title : $blockClass::defaultTitle;
        $this->addElementToEditGroup('text', 'title', array(
            'label' => __('Block Title'),
            'value' => $value,
            'required' => true,
        ));

        $value = $this->_blockConfig ? $this->_blockConfig->omeka_module : '';
        $this->addElementToEditGroup('select', 'omeka_module', array(
            'label' => __('Apply to plugin'),
            'multiOptions' => $this->_routesToPluginOptions(),
            'value' => $value,
        ));

        $value = $this->_blockConfig ? $this->_blockConfig->controller : '';
        $this->addElementToEditGroup('select', 'controller', array(
            'label' => __('Apply to page type'),
            'multiOptions' => $this->_routesToControllerOptions(),
            'value' => $value,
        ));

        $value = $this->_blockConfig ? $this->_blockConfig->action : '';
        $this->addElementToEditGroup('select', 'action', array(
            'label' => __('Apply to subpage type'),
            'multiOptions' => $this->_routesToActionOptions(),
            'value' => $value,
        ));

        $value = $this->_blockConfig ? $this->_blockConfig->id_request : '';
        $this->addElementToEditGroup('text', 'id_request', array(
            'label' => __('Apply to record id'),
            'value' => $value,
        ));

        $value = $this->_blockConfig ? $this->_blockConfig->custom_route : '';
        $this->addElementToEditGroup('text', 'custom_route', array(
            'label' => __('Apply to custom path'),
            'value' => $value,
        ));

        $value = $this->_blockConfig ? $this->_blockConfig->weight : 0;
        $this->addElementToEditGroup('select', 'weight', array(
            'label' => __('Weight'),
            'multiOptions' => array(
                '-5' => -5,
                '-4' => -4,
                '-3' => -3,
                '-2' => -2,
                '-1' => -1,
                '0' => 0,
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
            ),
            'value' => $value,
        ));

        $configData = $blockClass::formElementConfigData();
        if ($configData) {
            $configData['options']['value'] = $this->_blockConfig ? $this->_blockConfig->options : '';
            $this->addElementToEditGroup($configData['element'], 'options', $configData['options']);
        }

        $this->addElementToSaveGroup('submit', 'cancel', array(
            'label' => __('Cancel'),
            'class' => 'big blue button',
        ));

        if ($this->_blockConfig) {
            $this->addElementToSaveGroup('submit', 'delete-confirm', array(
                'label' => __('Delete'),
                'class' => 'big red button',
            ));
        }

        $this->addElement('sessionCsrfToken', 'csrf_token');
    }

    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    public function setBlockClass($blockClass)
    {
        $this->blockClass = $blockClass;
    }

    public function setBlockConfig($blockConfig)
    {
        $this->_blockConfig = $blockConfig;
    }

    private function _routesToPluginOptions()
    {
        $select = array();
        $select['any'] = __('Any');
        $select = array_merge($select, $this->_routesToFieldOptions('module'));
        return $select;
    }

    private function _routesToControllerOptions()
    {
        $select = array();
        $select['any'] = __('Any');
        $select = array_merge($select, $this->_routesToFieldOptions('controller'));

        return $select;
    }

    private function _routesToActionOptions()
    {
        $select = array();
        $select['any'] = __('Any');
        $select = array_merge($select, $this->_routesToFieldOptions('action'));
        return $select;
    }

    private function _routesToFieldOptions($field)
    {
        $select = array();
        foreach ($this->routes as $routeName => $route) {
            if (isset($route[$field])) {
                $select[$route[$field]] = $route[$field];
            }
        }

        $select = array_unique($select);
        return $select;
    }
}
