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

    const ATTRIBUTES = [
        'catid',
        'cat_title',
        'id',
        'title',
        'url',
        'description',
        'image',
    ];

    static public function render(string $template, array $data, bool $setSpans = false): string
    {
        $template = self::setPlaceholdersInTemplate($template, self::ATTRIBUTES);
        $data = self::getDataArrayWithPlaceholdersAsKey($data);
        $data = array_filter($data, function($item) {return is_string($item) || is_numeric($item); });
        if ($setSpans) {
            array_walk($data, function (&$item, $placeholder) {
                if (!is_string($item)) {
                    return $item;
                }
                $item = ModQlweblinksRender::generateSpan($item, $placeholder);
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
            $dataNew[$placeholder] = $content;
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
}
