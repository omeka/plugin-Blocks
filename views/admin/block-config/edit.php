<?php
$pageTitle = __('Configure Block');
queue_js_file('block-config');
echo head(array(
    'title' => $pageTitle,
    'bodyclass' => 'blocks',
    'content_class' => 'horizontal-nav',
));
?>
<div id="primary">
    <h2><?php echo __('Edit %s', $blockClass::name); ?></h2>
    <p><?php echo $blockClass::description; ?></p>
    <?php echo flash(); ?>
    <?php echo $this->form; ?>
</div>
<script type="text/javascript">
    Blocks.routes = <?php echo json_encode($this->routes); ?>;
</script>
<?php echo foot();?>
