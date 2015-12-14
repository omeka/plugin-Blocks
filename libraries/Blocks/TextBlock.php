<?php
class BlocksTextBlock extends Blocks_Block_Abstract
{
    const name = 'Text Block';
    const description = 'A general-purpose text block.';
    const defaultTitle = 'Text';
    const plugin = 'Blocks';

    public function isEmpty()
    {
        return false;
    }

    public function render()
    {
        $html = $this->_blockConfig->options;
        return $html;
    }

    static function prepareConfigOptions($formData)
    {
        // Just give back the contents of the textarea.
        return $formData;
    }

    static function formElementConfigData()
    {
        return array(
            'element' => 'textarea',
            'options' => array(
                'label' => __('Enter the text for your block'),
                'description' => __('HTML is okay. No PHP for you!'),
        ));
    }
}
