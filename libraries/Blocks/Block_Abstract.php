<?php

abstract class Blocks_Block_Abstract
{
    protected $_request;
    protected $_blockConfig;

    const defaultTitle = 'Title';
    const name = 'Block';
    const description = 'A block';
    // Plugins should state your name here.
    const plugin = null;

    public function __construct($request = null, $blockConfig = null)
    {
        $this->_request = $request;
        $this->_blockConfig = $blockConfig;
    }

    public function getBlockConfig()
    {
        return $this->_blockConfig;
    }

    public function getConfigTitle()
    {
        return $this->_blockConfig->title;
    }

    /**
     * Try to figure out what record is being displayed, if any
     * Each controller can stuff different variables into the view, so have to
     * approach this in a non-abstract way.
     *
     * @return Record|boolean
     */
    public function getRecord()
    {
        // @TODO: some plugins, like ExhibitBuilder, have a more complex
        // structure than just showAction. Punting on those for now.

        $view = get_view();
        if (isset($view->Collection)) {
            if ($this->_request->getActionName() != 'show') {
                return false;
            }
            return $view->Collection;
        }

        if (isset($view->Item)) {
            if ($this->_request->getActionName() != 'show') {
                return false;
            }
            return $view->Item;
        }
    }

    abstract public function isEmpty();

    abstract public function render();

    /**
     * Handles the form data for options to prepare it for saving, e.g.,
     * serializing an array
     *
     * @param $formData
     */
    static function prepareConfigOptions($formData) {}

    /**
     * Returns the formElement to use on the BlockConfig forms the element's
     * name must be set to 'options'
     *
     * @return Zend_Form_Element | string element to add. It will be given the
     * name 'options'
     */
    static function formElementConfigData() {}
}
