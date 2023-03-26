<?php

namespace ModQlweblinks\php\classes;

use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseQuery;

/**
 * @package        mod_qlqlweblinks
 * @copyright    Copyright (C) 2023 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
class ModQlweblinksDbQueries
{
    const TABLE_WEBLINKS = '#__weblinks';
    const TABLE_CATEGORIES = '#__categories';
    public \stdClass $module;
    public \JRegistry $params;
    public DatabaseDriver $db;

    public function __construct(\stdClass $module, \JRegistry $params, DatabaseDriver $db)
    {
        $this->module = $module;
        $this->params = $params;
        $this->db = $db;
    }

    public function getWeblinksAll(): array
    {
        return $this->getWeblinkByIds([]);
    }

    public function getWeblinkById(int $id): array
    {
        return $this->getWeblinkByIds([(int)$id]);
    }

    public function getWeblinkByIds(array $ids): array
    {
        $ids = $this->cleanArrayInt($ids);
        $query = $this->getQueryWeblinks();
        if (0 < count($ids)) {
            $query->where(sprintf('wl.id IN(%s)', implode(',', $ids)));
        }
        $this->db->setQuery($query);
        return $this->db->loadAssocList();
    }

    public function getWeblinksByCategoryId(int $categoryId): array
    {
        return $this->getWeblinksByCategoryIds([(int)$categoryId]);
    }

    public function getCategoriesAll(): array
    {
        return $this->getCategoriesByIds([]);
    }

    public function getCategoriesById(int $categoryIds): array
    {
        return $this->getCategoriesByIds([$categoryIds]);
    }

    public function getCategoriesByParentId(int $categoryId): array
    {
        $query = $this->getQueryCategories();
        $query->where(sprintf('parent_id = %s', $categoryId));
        $this->db->setQuery($query);
        return $this->db->loadAssocList();
    }

    public function getCategoriesByIds(array $categoryIds = []): array
    {
        $categoryIds = $this->cleanArrayInt($categoryIds);
        $query = $this->getQueryCategories();
        if (0 < count($categoryIds)) {
            $query->where(sprintf('id IN(%s)', implode(',', $categoryIds)));
        }
        $this->db->setQuery($query);
        return $this->db->loadAssocList();
    }

    public function getWeblinksByCategoryIds(array $categoryIds): array
    {
        $ids = $this->cleanArrayInt($categoryIds);
        $query = $this->getQueryWeblinks();
        if (0 < count($ids)) {
            $query->where(sprintf('wl.catid IN(%s)', implode(',', $categoryIds)));
        }
        $this->db->setQuery($query);
        return $this->db->loadAssocList();
    }

    private function getQueryWeblinks(): DatabaseQuery
    {
        $query = $this->getQuery();
        $query->select('wl.*, c.title as cat_title')->from(self::TABLE_WEBLINKS . ' as wl')->where('`state` = 1');
        $query->leftJoin(self::TABLE_CATEGORIES . ' as c', 'c.id = wl.catid');
        $where = '
            ( 
                (`publish_up` IS NULL AND `publish_down` IS NULL)
                OR
                (`publish_up` <= NOW() AND `publish_down` IS NULL)
                OR
                (`publish_up` IS NULL AND `publish_down` >= NOW())
                OR
                (`publish_up`<= NOW() AND `publish_down` >= NOW())
            )';
        $query->where($where);
        return $query;
    }

    private function getQueryCategories(): DatabaseQuery
    {
        $query = $this->getQuery();
        $query->select('*')->from(self::TABLE_CATEGORIES)->where('`published` = 1');
        $where = '`extension` = "com_weblinks"';
        return $query->where($where);
    }

    private function getQuery(): DatabaseQuery
    {
        return $this->db->getQuery(true);
    }

    private function cleanArrayInt($integers)
    {
        array_walk($integers, function(&$item) { return (int)$item; });
        return array_filter(array_unique($integers));
    }
}
