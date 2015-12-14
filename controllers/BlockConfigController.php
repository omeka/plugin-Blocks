<?php
/**
 * Controller for Blocks Config.
 * @package Blocks
 */
class Blocks_BlockConfigController extends Omeka_Controller_AbstractActionController
{
    protected $_autoCsrfProtection = true;

    private $_unusedRoutes = array(
        'default',
        'forbidden',
        'error',
        'id',
        'page',
        'pluginInstall',
        'fake_cron_fakecron',
    );

    public function init()
    {
        $this->_helper->db->setDefaultModelName('BlockConfig');
    }

    public function addAction()
    {
        if ($this->getRequest()->getParam('cancel')) {
            $this->_cancelAction();
            return;
        }

        $blockClass = $this->getRequest()->getParam('block-class');
        if (empty($blockClass)) {
            $this->_helper->redirector('browse');
        }

        $this->view->blockClass = $blockClass;
        $routesInfo = $this->_setupView();
        $form = $this->_getBlockConfigForm(false, $routesInfo, $blockClass);
        $this->view->form = $form;

        parent::addAction();
    }

    public function editAction()
    {
        if ($this->getRequest()->getParam('cancel')) {
            $this->_cancelAction();
            return;
        }

        if ($this->getRequest()->getParam('deleteconfirm')) {
            $this->_helper->redirector('delete-confirm', 'config', 'blocks', array(
                'id' => $this->getRequest()->getParam('id'),
            ));
            return;
        }

        $blockConfig =  $this->_helper->db->findById();
        $blockClass = $blockConfig->class_name;

        $this->view->blockClass = $blockClass;
        $routesInfo = $this->_setupView();
        $form = $this->_getBlockConfigForm($blockConfig, $routesInfo, $blockClass);
        $this->view->form = $form;

        parent::editAction();
    }

    protected function _cancelAction()
    {
        $this->_helper->_flashMessenger(__('The config has been canceled.'), 'success');
        $this->_helper->redirector('browse');
    }

    public function browseAction()
    {
        $blockClasses = unserialize(get_option('blocks'));
        $blocks = array();
        $blockConfigTable = $this->_helper->db;
        foreach ($blockClasses as $blockClass) {
            $blocks[$blockClass] = array(
                'name' => $blockClass::name,
                'description' => $blockClass::description,
                'configs' => $blockConfigTable->findBy(array('class_name' => $blockClass)),
            );
        }

        $this->view->assign(array('blocks' => $blocks));
    }

    protected function _getAddSuccessMessage($record)
    {
        return __('The block "%s" was successfully added!', $record->title);
    }

    protected function _getEditSuccessMessage($record)
    {
        return __('The block "%s" was successfully changed!', $record->title);
    }

    protected function  _getDeleteSuccessMessage($record)
    {
        return __('The block "%s" was successfully deleted!', $record->title);
    }

    protected function _getDeleteConfirmMessage($record)
    {
        return __('This will delete the block "%s".', $record->title);
    }

    /**
     * Redirect to another page after a record is successfully edited.
     *
     * @param Omeka_Record_AbstractRecord $record
     */
    protected function _redirectAfterEdit($record)
    {
        $this->_helper->redirector('browse');
    }

    /**
     * Adds some route info to the add and edit view to be used in configuring.
     */
    private function _setupView()
    {
        $router = $this->getFrontController()->getRouter();
        $routes = $router->getRoutes();
        // Kill some routes we don't care about.
        foreach ($this->_unusedRoutes as $route) {
            if (isset($routes[$route])) {
                unset($routes[$route]);
            }
        }
        $routesInfo = array();
        foreach ($routes as $routeName => $route) {
            $routesInfo[$routeName] = $route->getDefaults();
        }
        $routesInfo['itemsShow'] = array('action' => 'show', 'controller' => 'items');
        $routesInfo['itemsBrowse'] = array('action' => 'browse', 'controller' => 'items');
        $routesInfo['collectionsShow'] = array('action' => 'show', 'controller' => 'collections');
        $routesInfo['collectionsBrowse'] = array('action' => 'browse', 'controller' => 'collections');
        $routesInfo['filesShow'] = array('action' => 'show', 'controller' => 'files');

        $this->view->assign('routes', $routesInfo);
        return $routesInfo;
    }

    private function _getBlockConfigForm($blockConfig, $routesInfo, $blockClass)
    {
        require_once BLOCKS_PLUGIN_DIR . '/forms/BlockConfigForm.php';
        $form = new BlockConfigForm(array(
            'blockConfig' => $blockConfig,
            'routes' => $routesInfo,
            'blockClass' => $blockClass,
        ));
        return $form;
    }
}
