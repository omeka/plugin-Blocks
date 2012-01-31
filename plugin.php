<?php
define('BLOCKS_PLUGIN_DIR', dirname(__FILE__));
require_once BLOCKS_PLUGIN_DIR . '/BlocksPlugin.php';
require_once BLOCKS_PLUGIN_DIR . '/libraries/Blocks/BlocksManager.php';
require_once BLOCKS_PLUGIN_DIR . '/libraries/Blocks/Block_Abstract.php';
require_once BLOCKS_PLUGIN_DIR . '/helpers/blocks.php';

$blocksPlugin = new BlocksPlugin();
$blocksPlugin->setUp();


class BlocksTextBlock extends Blocks_Block_Abstract
{
    const name = "Text Block";
    const description = "A general-purpose text block.";
    const defaultTitle = "Text";
    const plugin = "Blocks";

    public function render()
    {
        $html = "<div class='block'>";
        $html .= "<h3>" . $this->blockConfig->title . "</h3>";
        $html .= "<div class='block-body'>";
        $html .= $this->blockConfig->options;
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    static function prepareConfigOptions($formData)
    {
        //just give back the contents of the textarea
        return $formData;
    }

    static function formElementConfigData()
    {
        $options = array(
        	'label'=>'Enter the text for your block',
        	'description' => "HTML is okay. NO PHP FOR YOU!"
        );
        $element = "textarea";
        return array('element'=>$element, 'options'=>$options);
    }
}

class BlocksCollectorsBlock extends Blocks_Block_Abstract
{
    const name = "Collectors Block";
    const description = "Display the collectors for a collection.";
    const defaultTitle = "Collectors";
    const plugin = "Blocks";

    public function render()
    {
        $collection = get_current_collection();
        if(is_null($collection)) {
            return false;
        }
        $collectorsArray = $collection->getCollectors();
        $html = "<div class='block'>";
        $html .= "<h3>" . $this->blockConfig->title . "</h3>";
        $html .= "<div class='block-body'>";
        $html .= "<ul class='blocks-collectors'>";
        foreach($collectorsArray as $collector) {
            $html .= "<li>$collector</li>";
        }
        $html .= "</ul>";
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    static function prepareConfigOptions($formData)
    {
        return null;
    }

    static function formElementConfigData()
    {
        return false;
    }
}

class BlocksCollectionItemBlock extends Blocks_Block_Abstract
{
    const name = "Collection Random Item Block";
    const description = "Display a random item for a collection.";
    const defaultTitle = "Collection Random Item Block";
    const plugin = "Blocks";

	public function render()
	{
	    $collection = get_current_collection();
	    $params = array('collection'=>$collection, 'random'=>1, 'hasImage'=>true);
	    $items = get_items($params, 1);
	    $item = $items[0];
	    return display_files_for_item($options = array(), $wrapperAttributes = array('class'=>'block-collection-item'), $item);

	}


    static function prepareConfigOptions($formData)
    {
        return null;
    }

    static function formElementConfigData()
    {
        return false;
    }
}