<?php
class BlocksCollectorsBlock extends Blocks_Block_Abstract
{
    const name = 'Collectors Block';
    const description = 'Display the collectors for a collection.';
    const defaultTitle = 'Collectors';
    const plugin = 'Blocks';

    public function isEmpty()
    {
        return false;
    }

    public function render()
    {
        $collection = get_current_collection();
        if (is_null($collection)) {
            return false;
        }
        $collectors = metadata($collection, array('Dublin Core', 'Contributor'), 'all');
        $html .= '<ul class="blocks-collectors">';
        foreach ($collectors as $collector) {
            $html .= '<li>' . $collector . '</li>';
        }
        $html .= '</ul>';
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
