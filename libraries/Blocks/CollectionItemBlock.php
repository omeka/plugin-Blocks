<?php
class BlocksCollectionItemBlock extends Blocks_Block_Abstract
{
    const name = 'Collection Random Item Block';
    const description = 'Display a random item for a collection.';
    const defaultTitle = 'Collection Random Item Block';
    const plugin = 'Blocks';

    public function isEmpty()
    {
        return false;
    }

    public function render()
    {
        $collection = get_current_record('collection');
        $params = array(
            'collection' => $collection,
            'random' => 1,
            'hasImage' => true,
        );
        $items = get_records('Item', $params, 1);
        if ($items) {
            $item = reset($items);
            return link_to_item(item_image('thumbnail', array(), 0, $item), array('class' => 'item-thumbnail block-collection-item'), 'show', $item);
        }
        return '';
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
