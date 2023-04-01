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
<span class="qlweblinks module_<?= $module->id ?>">
<?php foreach ($weblinks as $kw => $weblink): ?>
    <span class="ql-weblink-bare"><?php echo $weblink['content_with_span']; ?></span><?php if ($kw < array_key_last($weblinks)) echo ', '; ?>
<?php endforeach; ?>
</span>
