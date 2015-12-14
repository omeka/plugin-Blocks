<fieldset id="fieldset-blocks"><legend><?php echo __('Display Blocks'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('blocks_queue_css', __('Queue the Blocks css file')); ?>
        </div>
        <div class="inputs five columns omega">
            <?php echo $this->formCheckbox('blocks_queue_css', true,
                array('checked' => (boolean) get_option('blocks_queue_css'))); ?>
            <p class="explanation">
                <?php echo __('Check to load the blocks css file automatically.');
                echo ' ' . __('This css file is useless if styles are defined in the main css file.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('blocks_append_to_content_top', __('Automatically append to content top')); ?>
        </div>
        <div class="inputs five columns omega">
            <?php echo $this->formCheckbox('blocks_append_to_content_top', true,
                array('checked' => (boolean) get_option('blocks_append_to_content_top'))); ?>
            <p class="explanation">
                <?php echo __('Check to append blocks to content top automatically.');
                echo ' ' . __('If unchecked, "echo blocks();" should be added in each file of the theme or in a common file.'); ?>
            </p>
        </div>
    </div>
</fieldset>
