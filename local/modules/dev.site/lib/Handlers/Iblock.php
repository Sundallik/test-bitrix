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

        // Проверяем, что элемент не принадлежит инфоблоку LOG
        if ($arFields['IBLOCK_ID'] == self::getIBlockIdByCode('LOG')) {
            return;
        }

        // Получаем ID инфоблока LOG
        $logIBlockId = self::getIBlockIdByCode('LOG');
        if (!$logIBlockId) {
            return;
        }

        // Получаем информацию об инфоблоке, который изменяем
        $sourceIBlock = IblockTable::getRow([
            'filter' => ['=ID' => $arFields['IBLOCK_ID']],
            'select' => ['ID', 'NAME', 'CODE']
        ]);

        if (!$sourceIBlock) {
            return;
        }

        // Получаем или создаем раздел в инфоблоке LOG
        $sectionId = self::getOrCreateLogSection($logIBlockId, $sourceIBlock['NAME'], $sourceIBlock['CODE']);

        // Формируем данные для записи в лог
        $elementName = $arFields['ID'];
        $activeFrom = new DateTime();

        // Формируем описание для анонса
        $description = $sourceIBlock['NAME'];

        // Добавляем путь разделов, если элемент принадлежит разделу
        if (!empty($arFields['IBLOCK_SECTION_ID'])) {
            $sectionPath = self::getSectionPathRecursive($arFields['IBLOCK_SECTION_ID'], $arFields['IBLOCK_ID']);
            if (!empty($sectionPath)) {
                $description .= ' -> ' . implode(' -> ', $sectionPath);
            }
        }

        $description .= ' -> ' . $arFields['NAME'];

        // Создаем элемент в инфоблоке LOG
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
        // Проверяем существование раздела
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

        // Если раздела нет - создаем
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
