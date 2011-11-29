<?php

class BlockConfigForm extends Omeka_Form
{
    public $blockClass;
    public $routes;
    private $blockConfig;
    
    public function init()
    {
        $blockClass = $this->blockClass;
        parent::init();
        $this->setAttrib('id', 'blocks-config');
        $this->setMethod('post');
        
        $value = $this->blockConfig ? $this->blockConfig->title : $blockClass::defaultTitle;
        $this->addElement('text', 'title', array('label'=>'Block Title', 'value'=>$value));
        
        $value = $this->blockConfig ? $this->blockConfig->custom_route : '';
        $this->addElement('text', 'custom_route', array('label' => 'Apply to custom path', 'value' => $value));
        
        $value = $this->blockConfig ? $this->blockConfig->omeka_module : '';
        $this->addElement('select', 'omeka_module', array('label'=>'Apply to plugin',
        												  'multiOptions'=>$this->routesToPluginOptions(),
                                                          'value' => $value
        ));
        
        $value = $this->blockConfig ? $this->blockConfig->controller : '';
        $this->addElement('select', 'controller', array('label' =>'Apply to page type',
        												'multiOptions'=>$this->routesToControllerOptions(),
                                                        'value' => $value
                                                        ));
       
        $value = $this->blockConfig ? $this->blockConfig->action : '';
        $this->addElement('select', 'action', array('label' => 'Apply to subpage type',
        												'multiOptions'=>$this->routesToActionOptions(),
                                                        'value' => $value
                                                        ));
                
                                                        
        $value = $this->blockConfig ? $this->blockConfig->id_request : '';
        $this->addElement('text', 'id_request', array('label' => 'Apply to record id', 'value'=>$value));

        
        $configData = $blockClass::formElementConfigData();
        if($configData) {
            $configData['options']['value'] = $this->blockConfig ? $this->blockConfig->options : '';
            $this->addElement($configData['element'], 'options', $configData['options'] );
        }
        
        $value = $this->blockConfig ? $this->blockConfig->weight : 0;
        $this->addElement('select', 'weight', array('label' => 'Weight',
        											'multiOptions'=>array('-5'=>-5,
                                                        '-4' => -4,
                                                        '-3' => -3,
                                                        '-2' => -2,
                                                        '-1' => -1,
                                                        '0' => 0,
                                                        '1' => 1, '2'=>2, '3'=>3, '4'=>4, '5'=>5),
        
                                                    'value' => $value
                                                    ));
        
        $this->addElement('submit', 'Save');
        
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
        $this->blockConfig = $blockConfig;
    }
    
    private function routesToPluginOptions()
    {
        $selectArray = array('any' => 'Any');
        $selectArray = array_merge($selectArray, $this->routesToFieldOptions('module') );
        return $selectArray;
    }
    
    private function routesToControllerOptions()
    {
        $selectArray = array();
        $selectArray = array('any' => 'Any');
        $selectArray = array_merge($selectArray, $this->routesToFieldOptions('controller') );

        return $selectArray;
    }
    
    private function routesToActionOptions()
    {
        $selectArray = array();
        $selectArray = array('any' => 'Any');
        $selectArray = array_merge($selectArray, $this->routesToFieldOptions('action') );
        return $selectArray;
    }
    
    private function routesToFieldOptions($field)
    {
        
        foreach($this->routes as $routeName=>$route) {
            if(isset($route[$field])) {
                $selectArray[$route[$field]] = $route[$field];
            }
        }
        
        $selectArray = array_unique($selectArray);
        return $selectArray;
    }
    
}