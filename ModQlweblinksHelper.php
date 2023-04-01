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

    const TYPE_WEBLINK = 'weblink';
    const TYPE_CATEGORY = 'category';
    const DISPLAY_LIST = 'list';
    const DISPLAY_TABLE = 'table';
    const DISPLAY_BARE = 'bare';
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
        return self::filterWeblinksForCategory($categories, $Queries->getWeblinksByCategoryIds(array_column($categories, 'id')));
    }

    public function getCategoryByIdWithWeblinks(int $categoryId)
    {
        $Queries = $this->getQuery();
        $categories = $Queries->getCategoriesById($categoryId);
        $category = $categories[0] ?? [];
        $category['weblinks'] = self::filterWeblinksForCategory($categories, $Queries->getWeblinksByCategoryIds(array_column($categories, 'id')));
        return [$category];
    }

    public function getCategoriesByParentIdWithWeblinks(int $categoryId)
    {
        $Queries = $this->getQuery();
        $categories = $Queries->getCategoriesByParentId($categoryId);
        foreach ($categories as $k => $category) {
            if (!is_numeric($category['id'])) {
                continue;
            }
            $categories[$k]['weblinks'] = self::filterWeblinksForCategory($categories, $Queries->getWeblinksByCategoryId($category['id']));
        }
        return $categories;
    }

    public function getCategoriesByParent(int $categoryId)
    {
        $Queries = $this->getQuery();
        return $Queries->getCategoriesByParentId($categoryId);
    }

    public function renderAll(array $items, $type = self::TYPE_WEBLINK): array
    {
        $template = (self::TYPE_WEBLINK === $type)
            ? $this->params->get('weblink_template', 'title')
            : $this->params->get('category_template', 'title');
        $display = (self::TYPE_WEBLINK === $type)
            ? $this->params->get('weblink_display', 'list')
            : $this->params->get('category_display', 'list');
        foreach ($items as $k => $item) {
            $items[$k]['content'] = self::render($template, $item, $display);
            $items[$k]['content_with_span'] = self::render($template, $item, $display, true);
            if (self::TYPE_CATEGORY === $type && isset($items[$k]['weblinks']) && is_array($items[$k]['weblinks']) && count($items[$k]['weblinks']) > 0) {
                $items[$k]['weblinks'] = self::renderAll($items[$k]['weblinks'], self::TYPE_WEBLINK);
            }
        }
        return $items;
    }

    public static function render(string $template, array $item, string $display, bool $setLink = true, bool $setSpan = false): string
    {
        return trim(match ($display) {
            self::DISPLAY_TABLE => ModQlweblinksRender::renderTable($template, $item, $setLink = true, $setSpan),
            self::DISPLAY_BARE => ModQlweblinksRender::renderBare($template, $item, $setLink = true, $setSpan),
            default => ModQlweblinksRender::renderList($template, $item, $setLink = true, $setSpan),
        });
    }

    public function getQuery()
    {
        return new php\classes\ModQlweblinksDbQueries($this->module, $this->params, $this->db);
    }

    public static function filterWeblinksForCategory(array $categories, array $weblinks): array
    {
        $weblinks = self::chunkArrayByAttribute($weblinks, 'catid');
        foreach ($categories as $k => $category) {
            $catid = $category['id'];
            if (!isset($weblinks[$catid])) {
                continue;
            }
            return $weblinks[$catid];
        }
        return [];
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
