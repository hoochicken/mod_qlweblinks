<?php
/**
 * @package        mod_qlweblinks
 * @copyright    Copyright (C) 2023 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */
/** @var stdClass $module */
/** @var JRegistry $params */
/** @var array $items */
/** @var string $weblinkDisplay */
// $template = $params->get('');
?>
<table class="qlweblinks module_<?= $module->id ?>">
    <?php foreach ($items as $k => $item): ?>
    <tr><?php echo $item['content_with_span']; ?>
        <?php if (isset($item['weblinks']) && is_array($item['weblinks']) && 0 < count($item['weblinks'])) :
            $weblinks = $item['weblinks']; ?>
        <td>
            <table>
                <?php if (\ModQlweblinks\ModQlweblinksHelper::DISPLAY_TABLE === $weblinkDisplay) : ?>
                    <?php include 'default_weblinkstable.php'; ?>
                <?php elseif (\ModQlweblinks\ModQlweblinksHelper::DISPLAY_BARE === $weblinkDisplay) : ?>
                    <?php include 'default_weblinksbare.php'; ?>
                <?php else : ?>
                    <?php include 'default_weblinkslist.php'; ?>
                <?php endif; ?>
            </table>
        </td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>
</table>
