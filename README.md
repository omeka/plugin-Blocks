Blocks
======


Blocks are similar to widgets in WordPress or, you guessed it, blocks in Drupal.

The blocks configuration screen lets you assign blocks according to the URL path. Usually, you will want to assign a block to a page type (e.g., "items") and/or a subpage (e.g., "show"). For a specific page, you can go all the way down to the id. (Plugin developers will recognize that this works from the router: controller/action/id)

To make your theme display blocks, you will need to add the following code to all the files that should display blocks:

<div class="blocks" style="float: right">
<?php echo blocks(); ?>
</div>

This makes the most sense just before the primary div on each page.

== Creating New Blocks ==

Plugin writers can extend the class Blocks_Block_Abstract to create new kinds of blocks. 

Blocks must override the render() method, which returns the HTML to display in your new block.

Your plugin must register the blocks with the Blocks plugin on installation. The mechanism is simple. Get and 
unserialize the 'blocks' option, add the name of your class(es) that extend Blocks_Block_Abstract, and serialize it
back into the option.

```php
$blocks = unserialize(get_option('blocks'));
$blocks[] = 'MyFunkyBlock';
$blocks[] = 'MyEvenFunkierBlock';
set_option('blocks', serialize($blocks));
```

Also, follow the pattern of the two blocks in plugin.php.

