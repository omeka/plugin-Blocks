<?php


class BlockConfig extends Omeka_Record
{
    public $id;
    public $block_module;
    public $omeka_module;
    public $title;
    public $controller;
    public $action;
    public $id_request;
    public $custom_route;
    public $options;
    public $weight;
    public $class_name;
    
    public function saveForm($form, $blockClass)
    {
        
        $this->title = $form->getValue('title');
        $omeka_module = $form->getValue('omeka_module');
        
        if($omeka_module == 'any') {
            $this->omeka_module = NULL;
        } else {
            $this->omeka_module = $omeka_module;
        }
        
        $controller = $form->getValue('controller');
        if($controller == 'any') {
            $this->controller = NULL;
        } else {
            $this->controller = $controller;
        }
        $action = $form->getValue('action');
        if($action == 'any') {
            $this->action = NULL;
        } else {
            $this->action = $action;
        }
        
        $id_request = $form->getValue('id_request');
        if($id_request == '') {
            $this->id_request = NULL;
        } else {
            $this->id_request = $id_request;
        }

        $custom_route = $form->getValue('custom_route');
        if($custom_route == '') {
            $this->custom_route = NULL;
        } else {
            $this->custom_route = $custom_route;
        }
        
        $weight = $form->getValue('weight');
        if(!$weight) {
            $this->weight = 0;
        } else {
            $this->weight = $weight;
        }

        $this->options = $form->getValue('options');
        $this->class_name = $blockClass;
        $this->block_module = $blockClass::plugin;
        $this->save();
        
    }
    
    
    
    
}