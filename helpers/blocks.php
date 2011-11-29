<?php

//renders the html for blocks

function blocks()
{
    $blocksManager = new BlocksManager();
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $blocksManager->setRequest($request);
    $blocksManager->addBlocks();
    $blocksManager->renderBlocks();
    $html = $blocksManager->getHtml();
    return $html;
    
}