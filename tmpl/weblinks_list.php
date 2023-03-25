<?php
/**
 * @package		mod_qlweblinks
 * @copyright	Copyright (C) 2023 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
use Joomla\CMS\Factory;

/** @var stdClass $module */
/** @var array $items */
?>
<ul class="qlweblinks module_<?= $module->id ?>">
<?php foreach ($items as $k => $weblink): ?>
    <li><?php print_r($weblink); ?></li>
<?php endforeach; ?>
</ul>
