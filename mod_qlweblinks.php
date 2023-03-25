<?php
/**
 * @package		mod_qlweblinks
 * @copyright	Copyright (C) 2023 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace ModQlweblinks;
// no direct access
use JModuleHelper;

defined('_JEXEC') or die;
require_once dirname(__FILE__).'/php/classes/ModQlweblinksDbQueries.php';
require_once dirname(__FILE__).'/ModQlweblinksHelper.php';

/** @var $module  */
/** @var $params  */
$qlweblinksHelper = new ModQlweblinksHelper($module, $params, \JFactory::getDbo());
switch ($params->get('type')) {
    case 'categories_all':
        $items = $qlweblinksHelper->getCategories();
        break;
    case 'weblinks_all':
    default:
        $items = $qlweblinksHelper->getWeblinksAll();
        //$items = $qlweblinksHelper->renderWeblinks();
}
require JModuleHelper::getLayoutPath('mod_qlweblinks', $params->get('layout', 'default'));
