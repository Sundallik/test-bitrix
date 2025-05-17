<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult */
?>

<div class="news-list-ext">
    <?php foreach ($arResult['ITEMS'] as $iblockId => $items): ?>
        <div class="news-block" data-iblock="<?= $iblockId ?>">
            <h2 class="news-block-title">Инфоблок #<?= $iblockId ?></h2>

            <div class="news-items">
                <?php foreach ($items as $item): ?>
                    <div class="news-item">
                        <h3 class="news-item-title">
                            <a href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a>
                        </h3>

                        <?php if ($item['DATE_ACTIVE_FROM']): ?>
                            <div class="news-item-date"><?= $item['DATE_ACTIVE_FROM'] ?></div>
                        <?php endif; ?>

                        <?php if ($item['PREVIEW_TEXT']): ?>
                            <div class="news-item-preview"><?= $item['PREVIEW_TEXT'] ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
