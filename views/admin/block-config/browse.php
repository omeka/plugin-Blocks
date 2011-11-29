<?php
queue_css('blocks');
head(array('title' => 'Blocks', 'bodyclass' => 'primary',
    'content_class' => 'horizontal-nav'));


function blocks_display_request($blockConfig) {
    $path = '<dl class="blocks-block-configs">';
    $path .= '<dt>Pages</dt>';
    if(is_null($blockConfig->controller)) {
       $path .= '<dd>Any</dd>';
    } else {
        $path .= '<dd>' . $blockConfig->controller . '</dd>';
    }
    $path .= '<dt>Sub pages</dt>';
    if(is_null($blockConfig->action)) {
       $path .= '<dd>Any</dd>';
    } else {
        $path .= '<dd>' . $blockConfig->action . '</dd>';
    }
    $path .= '<dt>Page ID</dt>';
    if(is_null($blockConfig->id_request)) {
       $path .= '<dd>Any</dd>';
    } else {
        $path .= '<dd>' . $blockConfig->id_request . '</dd>';
    }
    $path .= '</dl>';
    return $path;
}

function blocks_block_config_edit_link($blockConfig)
{
    $html = "<a href='" . WEB_ROOT . "/admin/blocks/block-config/edit/id/" . $blockConfig->id . "'>" . $blockConfig->title . "</a>";
    return $html;
    
}
?>

<h1>Available Blocks</h1>
<?php //echo $this->navigation()->menu()->setUlClass('section-nav'); ?>

<div id="primary">
<table>
<tbody>

<?php foreach($this->blocks as $blockClass=>$block): ?>
<tr>
	<td><?php echo $block['name']; ?></td>
	<td><?php echo $block['description']; ?></td>
	<td><a href='<?php echo WEB_ROOT . "/admin/blocks/block-config/add?block-class=$blockClass" ?>'>Add a <?php echo $block['name']; ?></a>
	<?php if(!empty($block['configs'])): ?>
    	<h3>Already set up:</h3>
    	<?php foreach($block['configs'] as $config): ?>
    	<p>
    	<?php echo blocks_block_config_edit_link($config); ?>:
    	<?php echo blocks_display_request($config); ?>
    	</p>
    	<?php endforeach; ?>
	<?php endif; ?>
	</td>
</tr>

<?php endforeach; ?>
</tbody>

</table>



</div>


<?php foot(); ?>