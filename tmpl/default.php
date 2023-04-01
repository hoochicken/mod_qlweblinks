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
/** @var array $weblinks */
/** @var string $weblinkDisplay */
/** @var string $typeInGeneral */
/** @var \Joomla\Registry\Registry $params */

// no direct access
defined('_JEXEC') or die;
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerStyle('qlweblinks', 'mod_qlweblinks/styles.css');
$wa->useStyle('qlweblinks');

$weblinkDisplay = $params->get('weblink_display', 'list');
$categoryDisplay = $params->get('category_display', 'list');
?>
<div class="qlweblinks" id="module<?php echo $module->id ?>">
    <?php
    if ((\ModQlweblinks\ModQlweblinksHelper::TYPE_CATEGORY === $typeInGeneral && \ModQlweblinks\ModQlweblinksHelper::DISPLAY_TABLE === $categoryDisplay)
            ||
            (\ModQlweblinks\ModQlweblinksHelper::TYPE_WEBLINK === $typeInGeneral && \ModQlweblinks\ModQlweblinksHelper::DISPLAY_TABLE === $weblinkDisplay)) {
        include __DIR__ . '/default_table.php';
    } elseif (\ModQlweblinks\ModQlweblinksHelper::TYPE_WEBLINK === $typeInGeneral && \ModQlweblinks\ModQlweblinksHelper::DISPLAY_BARE === $weblinkDisplay) {
        $weblinks = $items;
        include __DIR__ . '/default_weblinksbare.php';
    } elseif (\ModQlweblinks\ModQlweblinksHelper::TYPE_CATEGORY === $typeInGeneral && \ModQlweblinks\ModQlweblinksHelper::DISPLAY_BARE === $categoryDisplay) {
        include __DIR__ . '/default_bare.php';
    } else {
        include __DIR__ . '/default_list.php';
    }
    ?>
</div>