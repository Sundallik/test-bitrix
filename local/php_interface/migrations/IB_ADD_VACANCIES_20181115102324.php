<?php

namespace Sprint\Migration;


use CUserOptions;

class IB_ADD_VACANCIES_20181115102324 extends Version
{

    protected $description = "Добавляет миграцию для иб Вакансии";

    public function up()
    {
        $helper = $this->getHelperManager();

        $iblockTypeId = 'CONTENT_RU';
        $helper->Iblock()->saveIblockType([
            'ID' => $iblockTypeId,
            'SECTIONS' => 'Y',
            'IN_RSS' => 'N',
            'SORT' => 100,
            'LANG' => [
                'ru' => [
                    'NAME' => 'Каталог',
                    'SECTION_NAME' => 'Разделы',
                    'ELEMENT_NAME' => 'Элементы'
                ],
                'en' => [
                    'NAME' => 'Catalog',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Elements'
                ],
            ]
        ]);

        $iblockId = $helper->Iblock()->saveIblock([
            'IBLOCK_TYPE_ID' => $iblockTypeId,
            'LID' => 's1',
            'CODE' => 'VACANCIES',
            'NAME' => 'Вакансии',
            'ACTIVE' => 'Y',
            'SORT' => 500,
            'LIST_PAGE_URL' => '',
            'DETAIL_PAGE_URL' => '',
            'SECTION_PAGE_URL' => '',
            'VERSION' => 2,
        ]);

        if (!$iblockId) {
            $this->outError('Ошибка создания инфоблока');
            return false;
        }

        $properties = [
            array(
                'CODE' => 'ACTIVITY',
                'NAME' => 'Тип занятости',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    ['VALUE' => 'Полная занятость', 'XML_ID' => 'POLN'],
                    ['VALUE' => 'Временная занятость', 'XML_ID' => 'VREMYAN'],
                    ['VALUE' => 'Частичная занятость', 'XML_ID' => 'CHATTICH'],
                    ['VALUE' => 'вечерние часы', 'XML_ID' => 'VECHER'],
                    ['VALUE' => 'в ночные часы', 'XML_ID' => 'NOCH'],
                    ['VALUE' => 'в выходные дни', 'XML_ID' => 'VIHODN'],
                    ['VALUE' => 'на летний период', 'XML_ID' => 'LETO'],
                    ['VALUE' => 'период', 'XML_ID' => 'PERIOD'],
                    ['VALUE' => 'Проектная', 'XML_ID' => 'PRAKTIKA'],
                    ['VALUE' => 'Стажировка', 'XML_ID' => 'STAJER'],
                    ['VALUE' => 'Дипломная практика', 'XML_ID' => 'DIPLOM_PRAKT'],
                ]
            ),
            array(
                'NAME' => 'Сфера деятельности',
                'CODE' => 'FIELD',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    ['XML_ID' => '1', 'VALUE' => 'Производство'],
                    ['XML_ID' => '2', 'VALUE' => 'Продажи'],
                    ['XML_ID' => '3', 'VALUE' => 'Маркетинг'],
                    ['XML_ID' => '4', 'VALUE' => 'Экономика и финансы'],
                    ['XML_ID' => '5', 'VALUE' => 'Бухгалтерский учет'],
                    ['XML_ID' => '6', 'VALUE' => 'Управление персоналом'],
                    ['XML_ID' => '7', 'VALUE' => 'Закупки'],
                    ['XML_ID' => '8', 'VALUE' => 'Логистика и транспорт'],
                    ['XML_ID' => '9', 'VALUE' => 'Техническое развитие'],
                    ['XML_ID' => '10', 'VALUE' => 'Инвестиции'],
                    ['XML_ID' => '11', 'VALUE' => 'Информационные технологии'],
                    ['XML_ID' => '12', 'VALUE' => 'Отдел промышленной безопасности, охраны труда и экологии'],
                    ['XML_ID' => '13', 'VALUE' => 'АХО'],
                    ['XML_ID' => '14', 'VALUE' => 'Финансовый анализ'],
                    ['XML_ID' => '15', 'VALUE' => 'Персонал'],
                    ['XML_ID' => '16', 'VALUE' => 'Безопасность'],
                    ['XML_ID' => '17', 'VALUE' => 'Служба развития производственной системы'],
                    ['XML_ID' => '18', 'VALUE' => 'Технический департамент'],
                    ['XML_ID' => '19', 'VALUE' => 'Служба по энергообеспечению и инфраструктуре'],
                ]
            ),
            array(
                'NAME' => 'Комбинат/Офис',
                'CODE' => 'OFFICE',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    ['XML_ID' => 'UST_ISHIM', 'VALUE' => 'СВЕЗА Тюмень (Усть-Ишимский филиал )'],
                    ['XML_ID' => 'URAL', 'VALUE' => 'СВЕЗА Уральский'],
                    ['XML_ID' => 'TYUMEN', 'VALUE' => 'СВЕЗА Тюмень'],
                    ['XML_ID' => 'UST_IZHORA', 'VALUE' => 'СВЕЗА Усть-Ижора'],
                    ['XML_ID' => 'NOVATOR', 'VALUE' => 'СВЕЗА Новатор'],
                    ['XML_ID' => 'MANTUROVO', 'VALUE' => 'СВЕЗА Мантурово'],
                    ['XML_ID' => 'KOSTROMA', 'VALUE' => 'СВЕЗА Кострома'],
                    ['XML_ID' => 'TOP_SINYACHIHA', 'VALUE' => 'СВЕЗА Верхняя Синячиха'],
                    ['XML_ID' => 'RESURS', 'VALUE' => 'Свеза Ресурс'],
                ]
            ),
            array(
                'NAME' => 'Электронная почта (e-mail)',
                'CODE' => 'EMAIL',
                'PROPERTY_TYPE' => 'S'
            ),
            array(
                'NAME' => 'Местоположение',
                'CODE' => 'LOCATION',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    ['XML_ID' => 'MOSCOW', 'VALUE' => 'Москва'],
                    ['XML_ID' => 'TUMEN', 'VALUE' => 'Тюмень'],
                    ['XML_ID' => 'OMSK', 'VALUE' => 'Усть-Ишим, Омская область'],
                    ['XML_ID' => 'PITER', 'VALUE' => 'Санкт-Петербург'],
                    ['XML_ID' => 'EBURG', 'VALUE' => 'Екатеринбург'],
                    ['XML_ID' => 'KOSTROMA', 'VALUE' => 'Кострома'],
                    ['XML_ID' => 'MANTUROVO', 'VALUE' => 'Мантурово, Костромская область'],
                    ['XML_ID' => 'NOVATOR', 'VALUE' => 'Новатор, Вологодская область'],
                    ['XML_ID' => 'URALSI', 'VALUE' => 'Уральский, Пермский край'],
                    ['XML_ID' => 'SINYACHIHA', 'VALUE' => 'Верхняя Синячиха, Свердловская область'],
                    ['XML_ID' => 'GAMBURG', 'VALUE' => 'Гамбург, Германия'
                    ]
                ]
            ),
            array(
                'NAME' => 'Тип вакансии',
                'CODE' => 'TYPE',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    ['XML_ID' => 'WORKERS', 'VALUE' => 'Рабочие'],
                    ['XML_ID' => 'SALES', 'VALUE' => 'Продажи'
                    ]
                ]
            ),
            array(
                'NAME' => 'Заработная плата',
                'CODE' => 'SALARY_TYPE',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    ['XML_ID' => 'AFTER', 'VALUE' => 'ОТ'],
                    ['XML_ID' => 'BEFORE', 'VALUE' => 'ДО'],
                    ['XML_ID' => 'EQUAL', 'VALUE' => '='],
                    ['XML_ID' => 'CONTRACT', 'VALUE' => 'Договорная'
                    ]
                ]
            ),
            array(
                'NAME' => 'Заработная плата (значение)',
                'CODE' => 'SALARY_VALUE',
                'PROPERTY_TYPE' => 'S'
            ),
            /**/
            array(
                'NAME' => 'Требования к соискателю',
                'CODE' => 'REQUIRE',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'Y',
                'ROW_COUNT' => 3,
                'COL_COUNT' => 90
            ),
            array(
                'NAME' => 'Основные обязанности',
                'CODE' => 'DUTY',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'Y',
                'ROW_COUNT' => 3,
                'COL_COUNT' => 90
            ),
            array(
                'NAME' => 'Условия работы',
                'CODE' => 'CONDITIONS',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'Y',
                'ROW_COUNT' => 3,
                'COL_COUNT' => 90
            ),
            array(
                'NAME' => 'График работы',
                'CODE' => 'SCHEDULE',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    ['XML_ID' => 'SMEN', 'VALUE' => 'Сменный график'],
                    ['XML_ID' => 'POLN', 'VALUE' => 'Полный день'
                    ]
                ]
            ),
            array(
                'NAME' => 'Дата размещения',
                'CODE' => 'DATE',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'DateTime'
            )
        ];

        foreach ($properties as $property) {
            $helper->Iblock()->saveProperty($iblockId, $property);
        }

        if ($iblockId) {
            CUserOptions::SetOption($iblockId, 'form_' . $iblockId, [
                'tabs' => [
                    [
                        'name' => 'Вакансия',
                        'fields' => [
                            'ACTIVE',
                            'PROPERTY_DATE',
                            'PROPERTY_TYPE',
                            'NAME',
                            'PROPERTY_ACTIVITY',
                            'PROPERTY_SCHEDULE',
                            'PROPERTY_FIELD',
                            'PROPERTY_OFFICE',
                            'PROPERTY_LOCATION',
                            'PROPERTY_EMAIL',
                            'PROPERTY_SALARY_TYPE',
                            'PROPERTY_SALARY_VALUE'
                        ]
                    ],
                    [
                        'name' => 'Описание вакансии',
                        'fields' => [
                            'PROPERTY_REQUIRE',
                            'PROPERTY_DUTY',
                            'PROPERTY_CONDITIONS'
                        ]
                    ]
                ], true
            ]);
        }

        return true;
    }

    public function down()
    {
        $helper = $this->getHelperManager();

        $helper->Iblock()->deleteIblockIfExists('VACANCIES', 'CONTENT_RU');
    }
}
