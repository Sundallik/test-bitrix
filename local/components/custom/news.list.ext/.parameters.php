<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

$arIBlockTypes = [];
if (Loader::includeModule('iblock')) {
    $res = CIBlockType::GetList(['SORT' => 'ASC']);
    while ($ar = $res->Fetch()) {
        if ($arType = CIBlockType::GetByIDLang($ar['ID'], LANGUAGE_ID)) {
            $arIBlockTypes[$ar['ID']] = '['.$ar['ID'].'] '.$arType['NAME'];
        }
    }
}

$arIBlocks = [];
if (isset($arCurrentValues['IBLOCK_TYPE']) && $arCurrentValues['IBLOCK_TYPE'] != '' && Loader::includeModule('iblock')) {
    $res = CIBlock::GetList(
        ['SORT' => 'ASC'],
        ['TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y']
    );
    while ($ar = $res->Fetch()) {
        $arIBlocks[$ar['ID']] = '['.$ar['ID'].'] '.$ar['NAME'];
    }
}

$arComponentParameters = [
    'PARAMETERS' => [
        'IBLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('NEWS_LIST_EXT_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockTypes,
            'DEFAULT' => 'news',
            'REFRESH' => 'Y',
        ],
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('NEWS_LIST_EXT_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'DEFAULT' => '',
            'ADDITIONAL_VALUES' => 'Y',
        ],
        'FILTER' => [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => 'Дополнительный фильтр',
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'MULTIPLE' => 'Y',
        ],
        'SORT_BY' => [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => 'Поле для сортировки',
            'TYPE' => 'STRING',
            'DEFAULT' => 'ACTIVE_FROM',
        ],
        'SORT_ORDER' => [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => 'Направление сортировки',
            'TYPE' => 'LIST',
            'VALUES' => [
                'ASC' => 'По возрастанию',
                'DESC' => 'По убыванию',
            ],
            'DEFAULT' => 'DESC',
        ],
        'CHECK_PERMISSIONS' => [
            'PARENT' => 'ADDITIONAL_SETTINGS',
            'NAME' => 'Проверять права доступа',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'CACHE_TIME' => ['DEFAULT' => 3600],
    ],
];
