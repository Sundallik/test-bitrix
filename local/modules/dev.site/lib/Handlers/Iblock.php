<?php

namespace DevSite\lib\Handlers;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Type\DateTime;
use CIBlockElement;
use CIBlockSection;

class Iblock
{
    public static function OnAfterIBlockElementAddHandler(&$arFields)
    {
        self::logIBlockElementChanges($arFields);
    }

    public static function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        self::logIBlockElementChanges($arFields);
    }

    private static function logIBlockElementChanges(&$arFields)
    {
        if (!Loader::includeModule('iblock')) {
            return;
        }

        if ($arFields['IBLOCK_ID'] == self::getIBlockIdByCode('LOG')) {
            return;
        }

        $logIBlockId = self::getIBlockIdByCode('LOG');
        if (!$logIBlockId) {
            return;
        }

        $sourceIBlock = IblockTable::getRow([
            'filter' => ['=ID' => $arFields['IBLOCK_ID']],
            'select' => ['ID', 'NAME', 'CODE']
        ]);

        if (!$sourceIBlock) {
            return;
        }

        $sectionId = self::getOrCreateLogSection($logIBlockId, $sourceIBlock['NAME'], $sourceIBlock['CODE']);

        $elementName = $arFields['ID'];
        $activeFrom = new DateTime();

        $description = $sourceIBlock['NAME'];

        if (!empty($arFields['IBLOCK_SECTION_ID'])) {
            $sectionPath = self::getSectionPathRecursive($arFields['IBLOCK_SECTION_ID'], $arFields['IBLOCK_ID']);
            if (!empty($sectionPath)) {
                $description .= ' -> ' . implode(' -> ', $sectionPath);
            }
        }

        $description .= ' -> ' . $arFields['NAME'];

        $el = new CIBlockElement;
        $result = $el->Add([
            'IBLOCK_ID' => $logIBlockId,
            'IBLOCK_SECTION_ID' => $sectionId,
            'NAME' => $elementName,
            'ACTIVE_FROM' => $activeFrom,
            'PREVIEW_TEXT' => $description,
            'ACTIVE' => 'Y'
        ]);
    }

    private static function getSectionPathRecursive($sectionId, $iblockId)
    {
        $path = [];

        while ($sectionId > 0) {
            $section = SectionTable::getRow([
                'filter' => ['=ID' => $sectionId, '=IBLOCK_ID' => $iblockId],
                'select' => ['ID', 'NAME', 'IBLOCK_SECTION_ID']
            ]);

            if (!$section) break;

            array_unshift($path, $section['NAME']);
            $sectionId = $section['IBLOCK_SECTION_ID'];
        }

        return $path;
    }

    private static function getIBlockIdByCode($code)
    {
        $iblock = IblockTable::getRow([
            'filter' => ['=CODE' => $code],
            'select' => ['ID']
        ]);

        return $iblock ? $iblock['ID'] : null;
    }

    private static function getOrCreateLogSection($logIBlockId, $sectionName, $sectionCode)
    {
        $section = SectionTable::getRow([
            'filter' => [
                '=IBLOCK_ID' => $logIBlockId,
                '=CODE' => $sectionCode
            ],
            'select' => ['ID']
        ]);

        if ($section) {
            return $section['ID'];
        }

        $bs = new CIBlockSection;
        $newSectionId = $bs->Add([
            'IBLOCK_ID' => $logIBlockId,
            'NAME' => $sectionName,
            'CODE' => $sectionCode,
            'ACTIVE' => 'Y'
        ]);

        return $newSectionId ?: 0;
    }
}
