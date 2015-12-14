<?php
class BlocksNotificationsBlock extends Blocks_Block_Abstract
{
    const name = 'Notification Block';
    const description = 'A general-purpose notifications block.';
    const defaultTitle = 'Notifications';
    const plugin = 'Blocks';

    public function isEmpty()
    {
        if (current_user()) {
            return false;
        }
        return true;
    }

    public function render()
    {
        $html = $this->_blockConfig->options;
        $notifications = apply_filters('blocks_notifications', array());
        foreach ($notifications as $notification) {
            $html .= '<h4>' . $notification['title'] . '</h4>';
            $html .= $notification['html'];
        }
        return $html;
    }

    static function prepareConfigOptions($formData)
    {
        // Just give back the contents of the textarea.
        return $formData;
    }

    static function formElementConfigData()
    {
        return false;
    }
}
