<?php
/**
 * @package		mod_qlweblinks
 * @copyright	Copyright (C) 2022 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
use Joomla\CMS\Factory;
// no direct access
defined('_JEXEC') or die;
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerStyle('qlweblinks', 'mod_qlweblinks/styles.css');
$wa->useStyle('qlweblinks');
?>

<div class="qlweblinks" id="module<?php echo $module->id ?>">
</div>