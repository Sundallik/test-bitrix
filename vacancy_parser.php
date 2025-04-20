<?php
require_once(dirname(__DIR__) . "/www/bitrix/modules/main/include/prolog_before.php");

if (!$USER->IsAdmin()) {
    die('Доступ запрещен');
}

if (!CModule::IncludeModule('iblock')) {
    die('Модуль инфоблоков не установлен');
}

$IBLOCK_ID = 8;
$CSV_FILE = 'vacancy.csv';

$el = new CIBlockElement;
$arProps = [];

$rsProp = CIBlockPropertyEnum::GetList(
    ["SORT" => "ASC", "VALUE" => "ASC"],
    ['IBLOCK_ID' => $IBLOCK_ID]
);
while ($arProp = $rsProp->Fetch()) {
    $key = trim(mb_strtolower($arProp['VALUE']));
    $arProps[$arProp['PROPERTY_CODE']][$key] = $arProp['ID'];
}

$rsElements = CIBlockElement::GetList([], ['IBLOCK_ID' => $IBLOCK_ID]);
while ($element = $rsElements->Fetch()) {
    CIBlockElement::Delete($element['ID']);
}

echo '<pre>';
if (($handle = fopen($CSV_FILE, "r")) !== false) {
    $header = fgetcsv($handle, 1000, ";"); // Пропускаем заголовок

    while (($data = fgetcsv($handle, 1000, ";")) !== false) {
        $PROP = [
            'ACTIVITY'    => $data[9]  ?? '',
            'FIELD'       => $data[11] ?? '',
            'OFFICE'      => $data[1]  ?? '',
            'LOCATION'    => $data[2]  ?? '',
            'REQUIRE'     => prepareMultilineText($data[4] ?? ''),
            'DUTY'        => prepareMultilineText($data[5] ?? ''),
            'CONDITIONS'  => prepareMultilineText($data[6] ?? ''),
            'EMAIL'       => $data[12] ?? '',
            'DATE'        => ConvertDateTime(date('d.m.Y'), "DD.MM.YYYY"),
            'TYPE'       => $data[8]  ?? '',
            'SALARY_TYPE' => '',
            'SALARY_VALUE'=> prepareSalary($data[7] ?? '', $arProps),
            'SCHEDULE'    => $data[10] ?? ''
        ];

        $arLoadProductArray = [
            "MODIFIED_BY"    => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => $IBLOCK_ID,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => trim($data[3]),
            "ACTIVE"         => (!empty(end($data)) ? 'Y' : 'N'),
        ];

        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo "Добавлена вакансия: " . $data[3] . " (ID: $PRODUCT_ID)<br>";
        } else {
            echo "Ошибка: " . $el->LAST_ERROR . "<br>";
        }
    }
    fclose($handle);
} else {
    die("Не удалось открыть файл $CSV_FILE");
}
echo '</pre>';

function prepareMultilineText($text) {
    $text = trim($text);
    if (empty($text)) return '';

    // Обработка маркированных списков
    if (strpos($text, '•') !== false) {
        return array_map('trim', explode('•', $text));
    }
    return $text;
}

function prepareSalary($value, $arProps) {
    $value = trim($value);
    if ($value == '-' || $value == 'по договоренности') return '';

    // Обработка "от 100 000 руб."
    if (preg_match('/^(от|до)\s(.+)/ui', $value, $matches)) {
        $type = mb_strtolower($matches[1]);
        $amount = $matches[2];

        if (isset($arProps['SALARY_TYPE'][$type])) {
            $PROP['SALARY_TYPE'] = $arProps['SALARY_TYPE'][$type];
            return $amount;
        }
    }

    return $value;
}

require_once(dirname(__DIR__) . "/www/bitrix/modules/main/include/epilog_after.php");


