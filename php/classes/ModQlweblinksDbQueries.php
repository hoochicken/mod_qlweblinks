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
        $query = $this->getQueryWeblinks();
        $this->db->setQuery($query);
        return $this->db->loadAssocList();
    }

    public function getWeblinksByCategoryIds(array $catids = []): array
    {
        $catids = $this->cleanArrayInt($catids);
        if (0 === count($catids)) {
            return [];
        }
        $query = $this->getQueryWeblinks();
        $query->where(sprintf('catid IN(%s)', implode(',', $catids)));
        $this->db->setQuery($query);
        return $this->db->loadAssocList();
    }

    public function getCategories(): array
    {
        $query = $this->getQueryCategories();
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
