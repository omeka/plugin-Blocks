<?php
/**
 * Renders the html for blocks.
 */
function blocks()
{
    $blocksManager = new BlocksManager;
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $blocksManager->setRequest($request);
    $blocksManager->addBlocks();
    $blocksManager->renderBlocks();

    $html = '<div class="blocks">';
    $html .= $blocksManager->getHtml();
    $html .= '</div>';
    return $html;
}

function blocks_unregister_blocks($blockNames)
{
    $blocks = unserialize(get_option('blocks'));
    $blocks = array_flip($blocks);
    if (is_array($blockNames)) {
        foreach ($blockNames as $blockName) {
            unset($blocks[$blockName]);
        }
    } else {
        unset($blocks[$blockNames]);
    }
    $blocks = array_flip($blocks);
    set_option('blocks', serialize($blocks));
}

function blocks_register_blocks($blockNames)
{
    $blocks = unserialize(get_option('blocks'));
    if (is_array($blockNames)) {
        $blocks = array_merge($blocks, $blockNames);
    } else {
        $blocks[] = $blockNames;
    }
    set_option('blocks', serialize($blocks));
}
