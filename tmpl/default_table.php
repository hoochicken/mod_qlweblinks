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
// $template = $params->get('');
?>
<table class="qlweblinks module_<?= $module->id ?>">
<?php foreach ($items as $k => $item): ?>
    <tr><?php echo $item['content_with_span']; ?>
        <!--ul>
            <?php if (isset($item['weblinks']) && is_array($item['weblinks']) && 0 < count($item['weblinks'])) foreach ($item['weblinks'] as $k2 => $weblink): ?>
                <li><?php echo $weblink['content_with_span']; ?></li>
            <?php endforeach; ?>
        </ul-->
    </tr>
<?php endforeach; ?>
</table>
