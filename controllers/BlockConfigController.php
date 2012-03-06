<?php

class Blocks_BlockConfigController extends Omeka_Controller_Action
{

    private $unusedRoutes = array(
            'default',
            'forbidden',
            'error',
            'id',
            'page',
            'pluginInstall',
            'fake_cron_fakecron'

        );


    public function init()
    {

        $this->_helper->db->setDefaultModelName('BlockConfig');
        $this->_modelClass = 'BlockConfig';
    }

    public function indexAction()
    {
        $this->_forward('browse');
    }

    public function editAction()
    {

        $blockConfig = $this->findById();
        $blockClass = $blockConfig->class_name;
        $this->view->blockClass = $blockClass;
        $routesInfo = $this->setupView();
        $form = $this->getBlockConfigForm($blockConfig, $routesInfo, $blockClass);
        $this->view->form = $form;


        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        $blockConfig->saveForm($form, $blockClass);
        $this->_forward('browse');
    }

    public function addAction()
    {
        $blockClass = $this->getRequest()->getParam('block-class');
        $this->view->blockClass = $blockClass;
        $routesInfo = $this->setupView();
        $form = $this->getBlockConfigForm(false, $routesInfo, $blockClass);
        $this->view->form = $form;


        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        $blockConfig = new BlockConfig();
        $blockConfig->saveForm($form, $blockClass);
        $this->_forward('browse');

    }


    public function browseAction()
    {
        $blockClasses = unserialize(get_option('blocks'));
        $blocks = array();
        $blockConfigTable = get_db()->getTable('BlockConfig');
        foreach($blockClasses as $blockClass)
        {
            $blocks[$blockClass] = array(
                    'name'=>$blockClass::name,
                    'description'=>$blockClass::description,
                    'configs'=>$blockConfigTable->findBy(array('class_name'=>$blockClass)),

                );
        }

        $this->view->assign('blocks', $blocks);

    }

    /**
     *
     * Adds some route info to the add and edit view to be used in configuring
     */

    private function setupView()
    {
        $router = Omeka_Context::getInstance()->getFrontController()->getRouter();
        $routes = $router->getRoutes();
        //kill some routes we don't care about
        foreach($this->unusedRoutes as $route) {
            if(isset($routes[$route])) {
                unset($routes[$route]);
            }
        }
        $routesInfo = array();
        foreach($routes as $routeName=>$route) {
            $routesInfo[$routeName] = $route->getDefaults();
        }
        $routesInfo['itemsShow'] = array('action'=>'show', 'controller'=>'items');
        $routesInfo['itemsBrowse'] = array('action'=>'browse', 'controller'=>'items');


        $routesInfo['collectionsShow'] = array('action'=>'show', 'controller'=>'collections');
        $routesInfo['collectionsBrowse'] = array('action'=>'browse', 'controller'=>'collections');

        $this->view->assign('routes', $routesInfo);
        return $routesInfo;
    }

    private function getBlockConfigForm($blockConfig = false, $routesInfo, $blockClass)
    {

        require_once BLOCKS_PLUGIN_DIR . '/forms/BlockConfigForm.php';
        $form = new BlockConfigForm(array('blockConfig'=>$blockConfig , 'routes'=>$routesInfo, 'blockClass'=>$blockClass));
        return $form;

    }
}