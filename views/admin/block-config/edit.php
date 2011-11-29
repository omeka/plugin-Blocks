<?php

queue_js('block-config');

head(array('title' => 'Configure New Block', 'bodyclass' => 'primary',
    'content_class' => 'horizontal-nav'));
$blockClass = $this->blockClass;
?>
<script type="text/javascript">
	Blocks.routes = <?php echo json_encode($this->routes); ?>;
</script>
<h1>Configure Block</h1>
<?php echo $this->navigation()->menu()->setUlClass('section-nav'); ?>

<div id="primary">
<h2>Edit <?php echo $blockClass::name; ?></h2>
<p><?php echo $blockClass::description; ?></p>
    <?php echo flash(); ?>

    <?php
    echo $this->form;
    ?>
<?php echo delete_button(null, 'delete'); ?>
</div>

<?php foot();?>