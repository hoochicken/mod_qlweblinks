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
?>
<span class="qlweblinks module_<?= $module->id ?>">
<?php foreach ($items as $kc => $item): ?>
    <span class="ql-category-bare"><?php echo $item['content_with_span']; ?></span><?php if (isset($item['weblinks']) && is_array($item['weblinks']) && 0 < count($item['weblinks'])) : ?><?php echo ': '; include 'default_weblinks.php'; ?>
    <?php endif; ?><?php if ($kc > 0 && $kc < array_key_last($item)) echo ', '; ?><?php if ($kc > 0 && $kc === array_key_last($item)) echo '; '; ?>
<?php endforeach; ?>
</span>
