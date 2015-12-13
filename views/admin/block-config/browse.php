<?php
$pageTitle = __('Blocks');
queue_css_file('blocks');
echo head(array(
    'title' => $pageTitle,
    'bodyclass' => 'blocks',
    'content_class' => 'horizontal-nav',
));

function blocks_display_request($blockConfig) {
    $path = '<dl class="blocks-block-configs">';
    $path .= '<dt>Pages</dt>';
    if (is_null($blockConfig->controller)) {
       $path .= '<dd>' . __('Any') . '</dd>';
    } else {
        $path .= '<dd>' . $blockConfig->controller . '</dd>';
    }
    $path .= '<dt>Sub pages</dt>';
    if (is_null($blockConfig->action)) {
       $path .= '<dd>' . __('Any') . '</dd>';
    } else {
        $path .= '<dd>' . $blockConfig->action . '</dd>';
    }
    $path .= '<dt>Page ID</dt>';
    if (is_null($blockConfig->id_request)) {
       $path .= '<dd>' . __('Any') . '</dd>';
    } else {
        $path .= '<dd>' . $blockConfig->id_request . '</dd>';
    }
    $path .= '</dl>';
    return $path;
}

function blocks_block_config_edit_link($blockConfig)
{
    $html = sprintf('<a href="%s">%s</a>',
        url(array(
                'module' => 'blocks',
                'controller' => 'block-config',
                'action' => 'edit',
                'id' => $blockConfig->id,
            ), 'default', array(), true),
        $blockConfig->title);
    return $html;
}
?>
<div id="primary">
    <h2><?php echo __('Available Blocks'); ?></h2>
    <?php echo flash(); ?>
    <table class="blocks-table">
    <thead>
        <tr>
            <?php
            $browseHeadings[__('Name')] = null;
            $browseHeadings[__('Description')] = null;
            $browseHeadings[__('List')] = null;
            echo browse_sort_links($browseHeadings, array('link_tag' => 'th scope="col"', 'list_tag' => ''));
            ?>
        </tr>
    </thead>
    <tbody>
    <?php $key = 0; ?>
    <?php foreach ($this->blocks as $blockClass=>$block): ?>
        <tr class="blocks-block <?php if (++$key % 2 == 1) echo 'odd'; else echo 'even'; ?>">
            <td class="blocks-name">
                <?php echo $block['name']; ?>
            </td>
            <td class="blocks-description">
                <?php echo $block['description']; ?>
            </td>
            <td>
                <a href="<?php echo url(array(
                        'module' => 'blocks',
                        'controller' => 'block-config',
                        'action' => 'add',
                        'block-class' => $blockClass,
                    ), 'default', array(), true); ?>">
                    <?php echo __('Add a %s', $block['name']); ?>
                </a>
            <?php if (!empty($block['configs'])): ?>
                <h4><?php echo __('Already set up:'); ?></h4>
                <?php foreach ($block['configs'] as $config): ?>
                <p>
                    <?php echo blocks_block_config_edit_link($config); ?>:
                    <?php echo blocks_display_request($config); ?>
                </p>
            <?php endforeach; ?>
            <?php else: ?>
                <h4><?php echo __('Set up: None'); ?></h4>
            <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
</div>
<?php echo foot(); ?>
