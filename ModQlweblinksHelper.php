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
        $Queries = new php\classes\ModQlweblinksDbQueries\ModQlweblinksDbQueries($this->module, $this->params, $this->db);
        return $Queries->getCategories();
    }
}
