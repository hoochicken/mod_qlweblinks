<?php

namespace ModQlweblinks\php\classes\ModQlweblinksDbQueries;

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

class ModQlweblinksRender
{

    const LINK_WEBLINK_TITLE = true;
    const TITLE = 'title';
    const ATTRIBUTES = [
        'catid',
        'cat_title',
        'id',
        'title',
        'url',
        'description',
        'image',
    ];

    static public function renderList(string $template, array $data, bool $setLink = true, bool $setSpans = false): string
    {
        return self::renderBare($template, $data, $setLink, $setSpans);
    }

    static public function renderTable(string $template, array $data, bool $setLink = true, bool $setSpans = false): string
    {
        $template = self::setPlaceholdersInTemplate($template, self::ATTRIBUTES);
        $template = str_replace(['(', ')'], '', $template);
        $template = ModQlweblinksRender::generateTemplateTableCells($template);

        $paramsWeblink = json_decode($data['params'] ?? '{}');
        $content = is_string($data['title']) ? trim($data['title']) : $data['title'];
        $target = !empty($paramsWeblink->target) ? $paramsWeblink->target : '_blank';
        $data['title'] = ($setLink && isset($data['url']))
            ? $data['title'] = self::generateLink($content, $data['url'], $target)
            : $content;
        $data['cat_title'] = isset($data['cat_title']) ? trim($data['cat_title']) : '';
        $data = self::getDataArrayWithPlaceholdersAsKey($data);
        $data = array_filter($data, function($item) { return is_string($item) || is_numeric($item); });

        return self::replacePlaceholders($template, $data);
    }

    static public function renderBare(string $template, array $data, bool $setLink = true, bool $setSpans = false): string
    {
        $template = self::setPlaceholdersInTemplate($template, self::ATTRIBUTES);
        $paramsWeblink = json_decode($data['params'] ?? '{}');
        $content = is_string($data['title']) ? trim($data['title']) : $data['title'];
        $target = !empty($paramsWeblink->target) ? $paramsWeblink->target : '_blank';
        $data['title'] = ($setLink && isset($data['url']))
            ? $data['title'] = self::generateLink($content, $data['url'], $target)
            : $content;
        $data = self::getDataArrayWithPlaceholdersAsKey($data);
        $data = array_filter($data, function($item) {return is_string($item) || is_numeric($item); });
        if ($setSpans) {
            array_walk($data, function (&$item, $placeholder) {
                if (!is_string($item)) {
                    return $item;
                }
                $class = strtolower(preg_replace('/[^(a-zA-Z0-9)]/', '', $placeholder));
                $item = ModQlweblinksRender::generateSpan($item, $class);
            });
        }
        return self::replacePlaceholders($template, $data);
    }

    static private function setPlaceholdersInTemplate(string $template, array $attributes): string
    {
        $placeholders = [];
        foreach ($attributes as $placeholderRaw) {
            $placeholder = self::generatePlaceholder($placeholderRaw);
            $placeholders[$placeholderRaw] = $placeholder;
        }
        return str_replace(array_keys($placeholders), $placeholders, $template);
    }

    static private function getDataArrayWithPlaceholdersAsKey(array $data): array
    {
        $dataNew = [];
        foreach($data as $attribute => $content) {
            $placeholder = self::generatePlaceholder($attribute);
            $dataNew[$placeholder] = is_string($content) ? trim($content) : $content;
        }
        return $dataNew;
    }

    static private function replacePlaceholders(string $template, array $attributes): string
    {
        return str_replace(array_keys($attributes), $attributes, $template);
    }

    static private function generatePlaceholder(string $attribute): string
    {
        return sprintf('{%s}', strtoupper($attribute));
    }

    static private function generateSpan(?string $content, string $attribute = ''): string
    {
        return sprintf('<span class="%s">%s</span>', $attribute, $content);
    }

    static private function generateLink(?string $content, string $url = '', ?string $target = '_blank'): string
    {
        return sprintf('<a href="%s" target="%s">%s</a>', $url, $target, $content);
    }

    static private function generateTemplateTableCells(?string $template): string
    {
        $columns = explode(' ', $template);
        array_walk($columns, function(&$item) {
            $class = strtolower(preg_replace('/[^(a-zA-Z0-9)]/', '', $item));
            $item = sprintf('<td class="%s">%s</td>', $class, $item);
        });
        return implode('', $columns);
    }
}
