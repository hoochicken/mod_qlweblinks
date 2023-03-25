<?php

namespace ModQlweblinks;

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

class ModQlweblinksHelper
{
    const TABLE_WEBLINKS = '#__weblinks';
    const TABLE_CATEGORIES = '#__categories';
    const NO_DATE = '0000-00-00 00:00:00';

    public \stdClass $module;
    public \JRegistry $params;
    public DatabaseDriver $db;

    function __construct(\stdClass $module, \JRegistry $params, DatabaseDriver $db)
    {
        $this->module = $module;
        $this->params = $params;
        $this->db = $db;
    }

    public function getWeblinksAll()
    {
        $query = $this->getQueryWeblinks();
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }

    public function getCategories()
    {
        $query = $this->getQueryCategories();
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }

    private function getQueryWeblinks(): DatabaseQuery
    {
        $query = $this->getQuery();
        $query->select('*')->from(self::TABLE_WEBLINKS)->where('`state` = 1');
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
}
