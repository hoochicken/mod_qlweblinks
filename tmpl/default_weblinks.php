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
$weblinkDisplay = $params->get('weblink_display', 'list');
if (!isset($item['weblinks']) || !is_array($item['weblinks']) || 0 === count($item['weblinks'])) {
    return;
}
$weblinks = $item['weblinks'];
if (\ModQlweblinks\ModQlweblinksHelper::DISPLAY_TABLE === $weblinkDisplay) {
    include __DIR__ . '/default_weblinkstable.php';
} elseif (\ModQlweblinks\ModQlweblinksHelper::DISPLAY_BARE === $weblinkDisplay) {
    include __DIR__ . '/default_weblinksbare.php';
} else {
    include __DIR__ . '/default_weblinkslist.php';
}
