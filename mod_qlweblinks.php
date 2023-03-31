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
use Joomla\CMS\Factory;

defined('_JEXEC') or die;
/** @var $module  */
/** @var $params  */
require_once dirname(__FILE__).'/php/classes/ModQlweblinksDbQueries.php';
require_once dirname(__FILE__).'/php/classes/ModQlweblinksRender.php';
require_once dirname(__FILE__).'/ModQlweblinksHelper.php';

$type = explode(':', $params->get('type'));
$typeInGeneral = $type[0] ?? 'weblink';
$type = $type[1] ?? 'all';
$weblinkIds = $params->get('weblinkIds', 0);
$categoryIds = $params->get('categoryIds', 0);

$qlweblinksHelper = new ModQlweblinksHelper($module, $params, Factory::getContainer()->get('DatabaseDriver'));
$items = match ($type) {
    'single' => $qlweblinksHelper->getWeblinkById($weblinkIds),
    'by_category' => $qlweblinksHelper->getWeblinksByCategoryId($categoryIds),
    'category' => $qlweblinksHelper->getCategoryById($categoryIds),
    'category_and_its_weblinks' => $qlweblinksHelper->getCategoryByIdWithWeblinks($categoryIds),
    'categories_and_their_weblinks' => $qlweblinksHelper->getCategoriesWithWeblinks(),
    'categories_by_parent' => $qlweblinksHelper->getCategoriesByParent($categoryIds),
    'categories_by_parent_and_their_weblinks' => $qlweblinksHelper->getCategoriesByParentIdWithWeblinks($categoryIds),
    'categories_all' => $qlweblinksHelper->getCategoriesAll(),
    default => $qlweblinksHelper->getWeblinksAll(),
};

$items = $qlweblinksHelper->renderAll($items, $typeInGeneral);
require JModuleHelper::getLayoutPath('mod_qlweblinks', $params->get('layout', 'default'));
