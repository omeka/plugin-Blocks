<?php
/**
 * @TODO: make this a singleton
 * @author patrickmj
 */
class BlocksManager
{
    public $request;
    public $blocks = array();
    public $html = '';

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function addBlocks()
    {
        $params = $this->request->getParams();
        // Add default parameters.
        $params += array(
            'module' => 'default',
            'controller' => 'index',
            'action' => 'index',
        );
        //unset($params['admin']);
        $params['sort_dir'] = 'ASC';
        $params['sort_field'] = 'weight';
        // BlocksTable will do the filtering to get the correct blocks.
        $blockConfigs = get_db()->getTable('BlockConfig')->findBy($params);
        foreach ($blockConfigs as $blockConfig) {
            $class = $blockConfig->class_name;
            $this->blocks[] = new $class($this->request, $blockConfig);
        }
    }

    public function renderBlocks()
    {
        foreach ($this->blocks as $block) {
            if (!$block->isEmpty()) {
                $html = $block->render();
                $blockClass = 'block_' . Inflector::underscore(get_class($block));
                $this->html .= '<div class="block ' . $blockClass . '">';
                $this->html .= '<h2>' . $block->getConfigTitle() . '</h2>';
                $this->html .= '<div class="block-body">';
                $this->html .= $block->render();
                $this->html .= '</div>';
                $this->html .= '</div>';
            }
        }
    }

    public function getHtml()
    {
        return $this->html;
    }
}
