<?php

namespace DevSite\lib\Agents;

use Bitrix\Main\Loader;
use CIBlockElement;

class Iblock
{
    public static function cleanUpOldLogs()
    {
        if (!Loader::includeModule('iblock')) {
            return "Iblock::cleanUpOldLogs();";
        }

        $logIBlockId = self::getIBlockIdByCode('LOG');
        if (!$logIBlockId) {
            return "Iblock::cleanUpOldLogs();";
        }

        // Получаем ID элементов, которые нужно оставить (10 самых новых)
        $elementsToKeep = [];
        $rs = CIBlockElement::GetList(
            ['ACTIVE_FROM' => 'DESC'],
            ['IBLOCK_ID' => $logIBlockId],
            false,
            ['nTopCount' => 10],
            ['ID']
        );

        while ($ar = $rs->Fetch()) {
            $elementsToKeep[] = $ar['ID'];
        }

        if (empty($elementsToKeep)) {
            return "Iblock::cleanUpOldLogs();";
        }

        // Удаляем все элементы, кроме тех, что нужно оставить
        $rsAll = CIBlockElement::GetList(
            ['ID' => 'ASC'],
            [
                'IBLOCK_ID' => $logIBlockId,
                '!ID' => $elementsToKeep
            ],
            false,
            false,
            ['ID']
        );

        $element = new CIBlockElement;
        while ($ar = $rsAll->Fetch()) {
            $element->Delete($ar['ID']);
        }

        return "Iblock::cleanUpOldLogs();";
    }

    private static function getIBlockIdByCode($code)
    {
        $iblock = \Bitrix\Iblock\IblockTable::getRow([
            'filter' => ['=CODE' => $code],
            'select' => ['ID']
        ]);

        return $iblock ? $iblock['ID'] : null;
    }
}
