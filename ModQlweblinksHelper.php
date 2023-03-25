<?php

namespace ModQlweblinks;

use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseQuery;
use ModQlweblinks\php\classes\ModQlweblinksDbQueries\ModQlweblinksRender;

/**
 * @package        mod_qlqlweblinks
 * @copyright    Copyright (C) 2023 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class ModQlweblinksHelper
{
    public \stdClass $module;
    public \JRegistry $params;
    public DatabaseDriver $db;

    public function __construct(\stdClass $module, \JRegistry $params, DatabaseDriver $db)
    {
        $this->module = $module;
        $this->params = $params;
        $this->db = $db;
    }

    public function getWeblinksAll()
    {
        $Queries = new php\classes\ModQlweblinksDbQueries($this->module, $this->params, $this->db);
        return $Queries->getWeblinksAll();
    }

    public function getCategories()
    {
        $Queries = new php\classes\ModQlweblinksDbQueries($this->module, $this->params, $this->db);
        return $Queries->getCategories();
    }

    public function getCategoriesWithWeblinks()
    {
        $Queries = new php\classes\ModQlweblinksDbQueries($this->module, $this->params, $this->db);
        $categories = $Queries->getCategories();
        return self::enrichCategoriesWithWeblinks($categories, $Queries->getWeblinksByCategoryIds(array_column($categories, 'id')));
    }

    public function renderAll(array $items): array
    {
        foreach ($items as $k => $item) {
            $items[$k]['content_with_span'] = ModQlweblinksRender::render($this->params->get('weblink_template', 'title'), $item, true);
            $items[$k]['content'] = ModQlweblinksRender::render($this->params->get('weblink_template', 'title'), $item);
        }
        return $items;
    }

    public static function enrichCategoriesWithWeblinks($categories, $weblinks): array
    {
        $weblinks = self::chunkArrayByAttribute($weblinks, 'catid');
        foreach ($categories as $k => $category) {
            $catid = $category['id'];
            $categories[$k]['weblinks'] = $weblinks[$catid] ?? [];
        }
        return $categories;
    }

    static private function chunkArrayByAttribute(array $data, string $attribute): array
    {
        $return = [];
        foreach ($data as $item) {
            $attributeValue = $item[$attribute] ?? 0;
            if (!isset($return[$attributeValue])) {
                $return[$attributeValue] = [];
            }
            $return[$attributeValue][] = $item;
        }
        return $return;
    }
}
