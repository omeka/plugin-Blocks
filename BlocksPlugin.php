<?php
/**
 * Blocks
 *
 * Blocks are similar to widgets in WordPress or, you guessed it, blocks in
 * Drupal.
 *
 * @copyright Copyright 2011-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

define('BLOCKS_PLUGIN_DIR', dirname(__FILE__));
require_once BLOCKS_PLUGIN_DIR . '/libraries/Blocks/BlocksManager.php';
require_once BLOCKS_PLUGIN_DIR . '/libraries/Blocks/Block_Abstract.php';
require_once BLOCKS_PLUGIN_DIR . '/libraries/Blocks/CollectionItemBlock.php';
require_once BLOCKS_PLUGIN_DIR . '/libraries/Blocks/CollectorsBlock.php';
require_once BLOCKS_PLUGIN_DIR . '/libraries/Blocks/NotificationsBlock.php';
require_once BLOCKS_PLUGIN_DIR . '/libraries/Blocks/TextBlock.php';
require_once BLOCKS_PLUGIN_DIR . '/helpers/blocks.php';

/**
 * The Stats plugin.
 *
 * @package Omeka\Plugins\Blocks
 */
class BlocksPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'install',
        'upgrade',
        'uninstall',
        'public_head',
        'public_content_top',
    );

    /**
     * @var array Filters for the plugin.
     */
    protected $_filters = array(
        'admin_navigation_main',
    );

    /**
     * @var array Options and their default values.
     */
    protected $_options = array(
        'blocks',
    );

    /**
     * Add the translations.
     */
    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');
    }

    /**
     * Install the plugin.
     */
    public function hookInstall()
    {
        $db = $this->_db;
        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->BlockConfig` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `block_module` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `class_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `title` text COLLATE utf8_unicode_ci NOT NULL,
            `omeka_module` varchar(255) COLLATE utf8_unicode_ci,
            `controller` varchar(255) COLLATE utf8_unicode_ci,
            `action` varchar(255) COLLATE utf8_unicode_ci,
            `id_request` int(10) unsigned DEFAULT NULL,
            `custom_route` text COLLATE utf8_unicode_ci,
            `weight` int(11) NOT NULL DEFAULT '0',
            `options` text COLLATE utf8_unicode_ci,
            PRIMARY KEY (`id`),
            INDEX `block_module` (`block_module`),
            INDEX `class_name` (`class_name`),
            INDEX `omeka_module` (`omeka_module`),
            INDEX `controller` (`controller`),
            INDEX `action` (`action`),
            INDEX `id_request` (`id_request`),
            INDEX `custom_route` (`custom_route`(255))
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
        ";
        $db->query($sql);

        // Set up our packaged blocks -- a list of class names that extend Blocks_Block_Abstract
        $this->_options['blocks'] = serialize(array(
            'BlocksTextBlock',
            'BlocksCollectorsBlock',
            'BlocksCollectionItemBlock',
            'BlocksNotificationsBlock'
        ));
        $this->_installOptions();
    }

    /**
     * Upgrade the plugin.
     */
    public function hookUpgrade($args)
    {
        $db = $this->_db;
        $old = $args['old_version'];
        $new = $args['new_version'];

        if (version_compare($old, '0.3', '<')) {
            $blocks = unserialize(get_option('blocks'));
            $blocks[] = 'BlocksNotificationsBlock';
            set_option('blocks', serialize($blocks));
        }

        if (version_compare($old, '2.3', '<')) {
            $sql = "ALTER TABLE `$db->BlockConfig`
                CHANGE `block_module` `block_module` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `id`,
                CHANGE `class_name` `class_name` varchar(255) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `block_module`,
                CHANGE `title` `title` text COLLATE utf8_unicode_ci NOT NULL after `class_name`,
                CHANGE `omeka_module` `omeka_module` varchar(255) COLLATE 'utf8_unicode_ci' NULL AFTER `title`,
                CHANGE `controller` `controller` varchar(255) COLLATE 'utf8_unicode_ci' NULL AFTER `omeka_module`,
                CHANGE `action` `action` varchar(255) COLLATE 'utf8_unicode_ci' NULL AFTER `controller`,
                CHANGE `weight` `weight` int(11) NOT NULL DEFAULT '0' AFTER `custom_route`,
                DROP INDEX `omeka_module`,
                ADD INDEX `block_module` (`block_module`),
                ADD INDEX `class_name` (`class_name`),
                ADD INDEX `omeka_module` (`omeka_module`),
                ADD INDEX `controller` (`controller`),
                ADD INDEX `action` (`action`),
                ADD INDEX `id_request` (`id_request`),
                ADD INDEX `custom_route` (`custom_route`(255))
            ";
            $db->query($sql);
        }
    }

    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {
        $db = $this->_db;
        $sql = "DROP TABLE IF EXISTS `$db->BlockConfig`";
        $db->query($sql);

        $this->_uninstallOptions();
    }

    public function hookPublicHead()
    {
        queue_css_file('blocks');
    }

    public function hookPublicContentTop()
    {
        echo blocks();
    }

    /**
     * Append a Contribution entry to the admin navigation.
     *
     * @param array $nav
     * @return array
     */
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Blocks'),
            'uri' => url(array(
                    'module' => 'blocks',
                    'controller' => 'block-config',
                ), 'default', array(), true),
            // 'resource' => 'Blocks_BlockConfig',
            // 'privilege' => 'browse'
        );
        return $nav;
    }
}
