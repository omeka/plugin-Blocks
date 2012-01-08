<?php

class BlocksPlugin extends Omeka_Plugin_Abstract
{
    protected $_hooks = array(
    	'install',
        'uninstall',
        'public_theme_header'
        
    );
    
    protected $_filters = array(
        'admin_navigation_main'
    );
    
    public function filterAdminNavigationMain($tabs)
    {
        $tabs['Blocks'] = uri('blocks/block-config');
        return $tabs;
        
    }
    
    public function hookPublicThemeHeader()
    {
        queue_css('blocks');
    }
    
    public function hookInstall()
    {
        $db = get_db();
        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->BlockConfig` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `block_module` tinytext COLLATE utf8_unicode_ci NOT NULL,
          `title` text COLLATE utf8_unicode_ci NOT NULL,
          `omeka_module` tinytext COLLATE utf8_unicode_ci,
          `controller` text COLLATE utf8_unicode_ci,
          `action` text COLLATE utf8_unicode_ci,
          `id_request` int(10) unsigned DEFAULT NULL,
          `custom_route` text COLLATE utf8_unicode_ci,
          `options` text COLLATE utf8_unicode_ci,
          `weight` int(11) NOT NULL DEFAULT '0',
          `class_name` text COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`id`),
          FULLTEXT KEY `omeka_module` (`omeka_module`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
        
        ";

        $db->exec($sql);
        
        //set up our packaged blocks -- a list of class names that extend Blocks_Block_Abstract
        $blocks = array(
            'BlocksTextBlock',
            'BlocksCollectorsBlock'
        );
        set_option('blocks', serialize($blocks));
        
    }
    
    public function hookUninstall()
    {
        $db = get_db();
        $sql = "DROP TABLE IF EXISTS `$db->BlockConfig`";
        $db->exec($sql);
        delete_option('blocks');
    }
    
}