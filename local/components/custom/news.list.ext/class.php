<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class NewsListExtComponent extends CBitrixComponent
{
    protected $errors = [];
    protected $filter = [];

    public function onPrepareComponentParams($arParams)
    {
        $arParams['IBLOCK_TYPE'] = trim($arParams['IBLOCK_TYPE']);
        $arParams['IBLOCK_ID'] = isset($arParams['IBLOCK_ID']) ? (int)$arParams['IBLOCK_ID'] : 0;
        $arParams['FILTER'] = is_array($arParams['FILTER']) ? $arParams['FILTER'] : [];
        $arParams['SORT_BY'] = $arParams['SORT_BY'] ?? 'ACTIVE_FROM';
        $arParams['SORT_ORDER'] = $arParams['SORT_ORDER'] ?? 'DESC';
        $arParams['CHECK_PERMISSIONS'] = $arParams['CHECK_PERMISSIONS'] ?? 'Y';
        $arParams['CACHE_TIME'] = isset($arParams['CACHE_TIME']) ? (int)$arParams['CACHE_TIME'] : 3600;

        return $arParams;
    }

    protected function checkRequiredParams()
    {
        if (empty($this->arParams['IBLOCK_TYPE'])) {
            $this->errors[] = 'Не указан тип инфоблока';
        }
    }

    protected function prepareFilter()
    {
        $this->filter = [
            'ACTIVE' => 'Y',
            'CHECK_PERMISSIONS' => $this->arParams['CHECK_PERMISSIONS'],
        ];

        if ($this->arParams['IBLOCK_ID'] > 0) {
            $this->filter['IBLOCK_ID'] = $this->arParams['IBLOCK_ID'];
        } else {
            $this->filter['IBLOCK_TYPE'] = $this->arParams['IBLOCK_TYPE'];
        }

        $this->filter = array_merge($this->filter, $this->arParams['FILTER']);
    }

    protected function getIblockIds()
    {
        if ($this->arParams['IBLOCK_ID'] > 0) {
            return [$this->arParams['IBLOCK_ID']];
        }

        $iblockIds = [];
        $res = CIBlock::GetList(
            [],
            [
                'TYPE' => $this->arParams['IBLOCK_TYPE'],
                'ACTIVE' => 'Y',
                'CHECK_PERMISSIONS' => $this->arParams['CHECK_PERMISSIONS'],
            ]
        );

        while ($iblock = $res->Fetch()) {
            $iblockIds[] = $iblock['ID'];
        }

        return $iblockIds;
    }

    protected function getElements()
    {
        $this->prepareFilter();

        $elements = [];
        $res = CIBlockElement::GetList(
            [
                $this->arParams['SORT_BY'] => $this->arParams['SORT_ORDER'],
            ],
            $this->filter,
            false,
            false,
            [
                'ID',
                'IBLOCK_ID',
                'NAME',
                'DATE_ACTIVE_FROM',
                'PREVIEW_TEXT',
                'DETAIL_PAGE_URL',
            ]
        );

        while ($element = $res->GetNext()) {
            $elements[$element['IBLOCK_ID']][] = $element;
        }

        return $elements;
    }

    public function executeComponent()
    {
        try {
            $this->checkRequiredParams();

            if (!empty($this->errors)) {
                $this->showErrors();
                return;
            }

            if ($this->StartResultCache()) {
                $this->arResult['ITEMS'] = $this->getElements();
                $this->arResult['IBLOCK_IDS'] = $this->getIblockIds();

                if (empty($this->arResult['ITEMS'])) {
                    $this->AbortResultCache();
                    $this->errors[] = 'Элементы не найдены';
                    $this->showErrors();
                } else {
                    $this->IncludeComponentTemplate();
                }
            }
        } catch (Exception $e) {
            $this->AbortResultCache();
            $this->errors[] = $e->getMessage();
            $this->showErrors();
        }
    }

    protected function showErrors()
    {
        foreach ($this->errors as $error) {
            ShowError($error);
        }
    }
}