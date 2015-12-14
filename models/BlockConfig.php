<?php

/**
 * @package Blocks\models
 */
class BlockConfig extends Omeka_Record_AbstractRecord
{
    public $id;
    public $block_module;
    public $class_name;
    public $title;
    public $omeka_module;
    public $controller;
    public $action;
    public $id_request;
    public $custom_route;
    public $options;
    public $weight = 0;

    /**
     * Set the POST data to the record.
     *
     * @see Omeka_Record_AbstractRecord::save()
     * @param array $post
     */
    public function setPostData($post)
    {
        // Set default and null values.
        foreach (array(
                'omeka_module',
                'controller',
                'action',
                'id_request',
                'custom_route',
                'options',
            ) as $key) {
            if (isset($post[$key]) && (empty($post[$key]) || $post[$key] == 'any')) {
                $post[$key] = null;
            }
        }
        if (isset($post['weight'])) {
            $post['weight'] = (integer) $post['weight'];
        }
        if (!isset($post['class_name']) && isset($post['block-class'])) {
            $post['class_name'] = $post['block-class'];
        }

        parent::setPostData($post);
    }

    /**
     * Template method for defining record validation rules.
     *
     * Should be overridden by subclasses.
     *
     * @return void
     */
    protected function _validate()
    {
        if (!class_exists($this->class_name)) {
            $this->addError('class_name', __('Block class should exists.'));
        }
    }

    /**
     * Executes before the record is saved.
     */
    protected function beforeSave($args)
    {
        $blockClass = $this->class_name;
        $this->block_module = class_exists($this->class_name)
            ? $blockClass::plugin
            : null;
    }
}
