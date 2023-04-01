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
$weblinkDisplay = $params->get('weblink_display', 'list');
?>
<ul class="qlweblinks module_<?= $module->id ?>">
<?php foreach ($items as $k => $item): ?>
    <li><?php echo $item['content_with_span']; ?>
        <?php if (isset($item['weblinks']) && is_array($item['weblinks']) && 0 < count($item['weblinks'])) : ?>
           <?php include 'default_weblinks.php'; ?>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
