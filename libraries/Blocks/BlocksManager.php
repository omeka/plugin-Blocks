<?php


/**
 *
 * @TODO: make this a singleton
 * @author patrickmj
 *
 */
class BlocksManager
{
    public $request;
    public $blocks = array();
    public $html= '';

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function addBlocks()
    {
        $requestInfo = $this->request->getParams();
        //unset($requestInfo['admin']);
        $requestInfo['sort_dir'] = 'ASC';
        $requestInfo['sort_field'] = 'weight';
        //BlocksTable will do the filtering to get the correct blocks
        $blockConfigs = get_db()->getTable('BlockConfig')->findBy($requestInfo);
        foreach($blockConfigs as $blockConfig) {
            $class = $blockConfig->class_name;
            $this->blocks[] = new $class($this->request, $blockConfig);
        }
    }

    public function renderBlocks()
    {
        foreach($this->blocks as $block) {
            try {
                $html = $block->render();
                if($html) {
                    $blockClass = 'block_' . Inflector::underscore(get_class($block));
                    $this->html .= "<div class='block $blockClass'>";
                    $this->html .= $html;
                    $this->html .= "</div>";
                }
            } catch (Exception $e) {
                _log($e);
            }
        }
    }

    public function getHtml()
    {
        return $this->html;
    }

}