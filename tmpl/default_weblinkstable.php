<?php
/**
 * @package		mod_qlweblinks
 * @copyright	Copyright (C) 2023 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
/** @var stdClass $module */
/** @var JRegistry $params */
/** @var array $weblinks */
?>
<table class="qlweblinks module_<?= $module->id ?>">
<?php foreach ($weblinks as $k => $weblink): ?>
    <tr><?php echo $weblink['content_with_span']; ?></tr>
<?php endforeach; ?>
</table>
