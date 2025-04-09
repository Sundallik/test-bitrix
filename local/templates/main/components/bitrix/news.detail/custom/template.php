<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="article-card">
    <?if((!isset($arParams["DISPLAY_NAME"]) || $arParams["DISPLAY_NAME"]!="N") && $arResult["NAME"]):?>
        <div class="article-card__title"><?=$arResult["NAME"]?></div>
    <?endif;?>

    <?if((!isset($arParams["DISPLAY_DATE"]) || $arParams["DISPLAY_DATE"]!="N") && $arResult["DISPLAY_ACTIVE_FROM"]):?>
        <div class="article-card__date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></div>
    <?endif;?>

    <div class="article-card__content">
        <?if((!isset($arParams["DISPLAY_PICTURE"]) || $arParams["DISPLAY_PICTURE"]!="N") && is_array($arResult["DETAIL_PICTURE"])):?>
            <div class="article-card__image sticky">
                <img
                        src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
                        alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
                        title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
                        data-object-fit="cover"
                />
            </div>
        <?endif?>

        <div class="article-card__text">
            <?if($arResult["DETAIL_TEXT"] <> ''):?>
                <div class="block-content" data-anim="anim-3">
                    <?echo $arResult["DETAIL_TEXT"];?>
                </div>
            <?else:?>
                <div class="block-content" data-anim="anim-3">
                    <?echo $arResult["PREVIEW_TEXT"];?>
                </div>
            <?endif?>

            <a class="article-card__button" href="<?=htmlspecialcharsbx($arResult['LIST_PAGE_URL'] ?? $arParams['SEF_FOLDER'] ?? '/news/')?>">Назад к новостям</a>
        </div>
    </div>
</div>