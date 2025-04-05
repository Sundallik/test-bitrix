<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<div class="contact-form">
    <?php if ($arResult["isFormErrors"] == "Y"): ?>
        <div class="error-message"><?=$arResult["FORM_ERRORS_TEXT"]?></div>
    <?php endif; ?>

    <?= $arResult["FORM_NOTE"] ?? '' ?>

    <?php if ($arResult["isFormNote"] != "Y"): ?>
        <?=$arResult["FORM_HEADER"]?>

        <div class="contact-form__head">
            <div class="contact-form__head-title"><?=$arResult["FORM_TITLE"]?></div>
            <?php if ($arResult["isFormDescription"] == "Y"): ?>
                <div class="contact-form__head-text"><?=$arResult["FORM_DESCRIPTION"]?></div>
            <?php endif; ?>
        </div>

        <div class="contact-form__form">
            <div class="contact-form__form-inputs">
                <?php foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
                    <?php if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'hidden' && $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] !== 'textarea'): ?>
                        <div class="input contact-form__input">
                            <label class="input__label">
                                <div class="input__label-text">
                                    <?=$arQuestion["CAPTION"]?>
                                    <?php if ($arQuestion["REQUIRED"] == "Y"): ?>
                                        <span class="required">*</span>
                                    <?php endif; ?>
                                </div>
                                <?php
                                    $htmlCode = str_replace(
                                        ['<input', '<select'],
                                        ['<input class="input__input"', '<select class="input__input"'],
                                        $arQuestion["HTML_CODE"]
                                    );
                                echo $htmlCode;
                                ?>
                                <?php if (isset($arResult["FORM_ERRORS"][$FIELD_SID])): ?>
                                    <div class="input__notification"><?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?></div>
                                <?php endif; ?>
                            </label>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="contact-form__form-message">
                <?php foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
                    <?php if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'hidden' && $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'): ?>
                        <div class="input">
                            <label class="input__label" for="medicine_message">
                                <div class="input__label-text">
                                    <?=$arQuestion["CAPTION"]?>
                                    <?php if ($arQuestion["REQUIRED"] == "Y"): ?>
                                        <span class="required">*</span>
                                    <?php endif; ?>
                                </div>
                                <?php
                                    $htmlCode = str_replace(
                                        ['<textarea'],
                                        ['<textarea class="input__input"'],
                                        $arQuestion["HTML_CODE"]
                                    );
                                echo $htmlCode;
                                ?>
                                <?php if (isset($arResult["FORM_ERRORS"][$FIELD_SID])): ?>
                                    <div class="input__notification"><?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?></div>
                                <?php endif; ?>
                            </label>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?php if($arResult["isUseCaptcha"] == "Y"): ?>
                <div class="captcha-block">
                    <div class="input">
                        <label class="input__label">
                            <div class="input__label-text">
                                <?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?>
                                <?=$arResult["REQUIRED_SIGN"];?>
                            </div>
                            <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" alt="CAPTCHA" />
                            <input type="text" name="captcha_word" size="30" maxlength="50" value="" class="input__input" />
                        </label>
                    </div>
                </div>
            <?php endif; ?>

            <div class="contact-form__bottom">
                <div class="contact-form__bottom-policy">
                    Нажимая «Отправить», Вы подтверждаете, что ознакомлены, полностью согласны и принимаете условия «Согласия на обработку персональных данных».
                </div>

                <button class="form-button contact-form__bottom-button" type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>">
                    <div class="form-button__title"><?=htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?></div>
                </button>

<!--                --><?php //if ($arResult["F_RIGHT"] >= 15): ?>
<!--                    <input type="hidden" name="web_form_apply" value="Y" />-->
<!--                    <input type="submit" name="web_form_apply" value="--><?php //=GetMessage("FORM_APPLY")?><!--" class="form-button" />-->
<!--                --><?php //endif; ?>
            </div>
        </div>

        <?=$arResult["FORM_FOOTER"]?>
    <?php endif; ?>
</div>
