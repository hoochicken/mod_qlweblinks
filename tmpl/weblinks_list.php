<?php
/**
 * @package		mod_qlweblinks
 * @copyright	Copyright (C) 2023 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
/** @var stdClass $module */
/** @var JRegistry $params */
/** @var array $items */
$template = $params->get('')
?>
<ul class="qlweblinks module_<?= $module->id ?>">
<?php foreach ($items as $k => $weblink): ?>
    <li><?php echo $weblink['content_with_span']; ?></li>
    <!--li><?php echo $weblink['content']; ?></li-->
<?php endforeach; ?>
</ul>
