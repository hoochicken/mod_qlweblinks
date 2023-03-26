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

    const TEMPLATE_WEBLINK = 'weblink';
    const TEMPLATE_CATEGORY = 'category';

    public function __construct(\stdClass $module, \JRegistry $params, DatabaseDriver $db)
    {
        $this->module = $module;
        $this->params = $params;
        $this->db = $db;
    }

    public function getWeblinksAll()
    {
        $Queries = $this->getQuery();
        return $Queries->getWeblinksAll();
    }

    public function getWeblinkById(int $id)
    {
        $Queries = $this->getQuery();
        return $Queries->getWeblinkById($id);
    }

    public function getWeblinkByIds(array $ids)
    {
        $Queries = $this->getQuery();
        return $Queries->getWeblinkByIds($ids);
    }

    public function getWeblinksByCategoryId(int $id)
    {
        $Queries = $this->getQuery();
        return $Queries->getWeblinksByCategoryId($id);
    }

    public function getWeblinksByCategoryIds(array $ids)
    {
        $Queries = $this->getQuery();
        return $Queries->getWeblinksByCategoryIds($ids);
    }

    public function getCategoryById(int $categoryId): array
    {
        $Queries = $this->getQuery();
        return $Queries->getCategoriesById($categoryId);
    }

    public function getCategoriesAll()
    {
        $Queries = $this->getQuery();
        return $Queries->getCategoriesAll();
    }

    public function getCategoriesWithWeblinks()
    {
        $Queries = $this->getQuery();
        $categories = $Queries->getCategoriesAll();
        return self::enrichCategoriesWithWeblinks($categories, $Queries->getWeblinksByCategoryIds(array_column($categories, 'id')));
    }

    public function getCategoryByIdWithWeblinks(int $categoryId)
    {
        $Queries = $this->getQuery();
        $categories = $Queries->getCategoriesById($categoryId);
        return self::enrichCategoriesWithWeblinks($categories, $Queries->getWeblinksByCategoryId($categoryId));
    }

    public function getCategoriesByParentIdWithWeblinks(int $categoryId)
    {
        $Queries = $this->getQuery();
        $categories = $Queries->getCategoriesByParentId($categoryId);
        return self::enrichCategoriesWithWeblinks($categories, $Queries->getWeblinksByCategoryId($categoryId));
    }

    public function renderAll(array $items, $type = self::TEMPLATE_WEBLINK): array
    {
        $template = (self::TEMPLATE_WEBLINK === $type)
            ? $this->params->get('weblink_template', 'title')
            : $this->params->get('wcategory_display', 'title');
        foreach ($items as $k => $item) {
            $items[$k]['content_with_span'] = ModQlweblinksRender::render($template, $item, true);
            $items[$k]['content'] = ModQlweblinksRender::render($template, $item);
            if (self::TEMPLATE_CATEGORY === $type) {
                $items[$k]['weblinks'] = self::renderAll($items[$k]['weblinks'], self::TEMPLATE_WEBLINK);
            }
        }
        return $items;
    }

    public function getQuery()
    {
        return new php\classes\ModQlweblinksDbQueries($this->module, $this->params, $this->db);;
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
