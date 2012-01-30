<?php


abstract class Blocks_Block_Abstract
{
    protected $request;
    protected $blockConfig;

    const defaultTitle = "Title";
    const name = "Block";
    const description = "A block";
    const plugin = null; // plugins should state your name here

    public function __construct($request = null, $blockConfig = null)
    {
        $this->request = $request;
        $this->blockConfig = $blockConfig;
    }

    /**
     *
     * Try to figure out what record is being displayed, if any
     * Each controller can stuff different variables into the view,
     * so have to approach this in a non-abstract way.
     */

    public function getRecord()
    {

        //@TODO: some plugins, like ExhibitBuilder, have a more complex structure than just
        //showAction. Punting on those for now.

        $view = __v();
        if(isset($view->Collection)) {
            if($this->request->getActionName() != 'show') {
                return false;
            }
            return $view->Collection;
        }

        if(isset($view->Item)) {
            if($this->request->getActionName() != 'show') {
                return false;
            }
            return $view->Item;
        }
    }

    abstract public function render();

    /**
     *
     * handles the form data for options to prepare it for saving, e.g., serializing an array
     * @param $formData
     */

    static function prepareConfigOptions($formData) {}

    /**
     *
     * returns the formElement to use on the BlockConfig forms
     * the element's name must be set to 'options'
     * @return Zend_Form_Element | string element to add. It will be given the name 'options'
     */
    static function formElementConfigData() {}

}