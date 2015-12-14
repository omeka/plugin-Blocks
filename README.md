Blocks (plugin for Omeka)
========================

[Blocks] is a plugin for [Omeka] that provides blocks that are similar to
widgets in WordPress or, you guessed it, blocks in Drupal.


Installation
------------

Uncompress files and rename plugin folder "Blocks".

Then install it like any other Omeka plugin and follow the config instructions.


Configuration
-------------

The blocks configuration screen lets you assign blocks according to the URL
path. Usually, you will want to assign a block to a page type (e.g., "items")
and/or a subpage (e.g., "show"). For a specific page, you can go all the way
down to the id. (Plugin developers will recognize that this works from the
router: controller/action/id)

To make your theme display blocks, you will need to add the following code to
all the files that should display blocks:

```html
<div class="blocks" style="float: right">
    <?php echo blocks(); ?>
</div>
```

This makes the most sense just before the primary div on each page.


Creating New Blocks
-------------------

Plugin writers can extend the class Blocks_Block_Abstract to create new kinds of
blocks.

Blocks must override the render() method, which returns the HTML to display in
your new block.

Your plugin must register the blocks with the Blocks plugin on installation. The
mechanism is simple. Get and unserialize the 'blocks' option, add the name of
your class(es) that extend Blocks_Block_Abstract, and serialize it back into the
option. This would make most sense in the hookInstall() method of your plugin.
It would be polite to similarly remove them in hookUninstall().

```php
$blocks = unserialize(get_option('blocks'));
$blocks[] = 'MyFunkyBlock';
$blocks[] = 'MyEvenFunkierBlock';
set_option('blocks', serialize($blocks));
```

Also, follow the pattern of the default blocks in the libraries/blocks folder.


Warning
-------

Use it at your own risk.

It's always recommended to backup your files and database regularly so you can
roll back if needed.


Troubleshooting
---------------

See online issues on the [Blocks issues] page on [Omeka].


License
-------

This plugin is published under [GNU/GPL].

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.


Contact
-------

Current maintainers:
* [Center for History & New Media]


Copyright
---------

* Copyright Center for History and New Media, 2011-2012
* Copyright Daniel Berthereau, 2014 [Daniel-KM] (upgrade to Omeka 2.2)


[Omeka]: https://omeka.org "Omeka.org"
[Blocks]: https://github.com/omeka/plugin-Blocks
[Blocks issues]: https://omeka.org/forums/forum/plugins
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[Center for History & New Media]: http://chnm.gmu.edu
[Daniel-KM]: https://github.com/Daniel-KM "Daniel Berthereau"
