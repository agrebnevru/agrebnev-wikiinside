<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Security\Random;
use Bitrix\Main\UI\Extension;
use Bitrix\Main\Web\Json;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var SaleOrderAjax $component
 * @var string $templateName
 * @var string $templateFolder
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 */

$this->addExternalJs($templateFolder . '/dist/component.js');
$this->addExternalCss($templateFolder . '/dist/component.css');

$blockId = 'agrebnev_wikiinside_data-' . Random::getString(5);

// echo "\$_REQUEST =<br><textarea>";
// print_r($_REQUEST);
// echo "</textarea><br>";

?>

<div id="<?= $blockId ?>" class="b-agrebnev-wikiinside">
    <?php
    if ('Y' === $arParams['IFRAME_REQUEST']): ?>
        <div class="b-agrebnev-wikiinside__header">
            <div class="b-agrebnev-wikiinside__header__left"> &nbsp; &nbsp;</div>
            <div class="b-agrebnev-wikiinside__header__title">
                <?= Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_HEADER__TITLE') ?>
            </div>
            <div class="b-agrebnev-wikiinside__header__right">
                <a
                        href="<?= $arResult['BASE_URL'] ?>"
                        target="_blank"
                        title="<?= Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_HEADER__BASE_TITLE') ?>"
                ><?= Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_HEADER__BASE') ?></a>
                <a
                        class="js-agrebnev-wikiinside-addnew"
                        href="<?= $arResult['ADD_URL'] ?>"
                        target="_blank"
                        title="<?= Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_HEADER__ADD_HERE_TITLE') ?>"
                ><?= Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_HEADER__ADD_HERE') ?></a>
            </div>
        </div>
    <?php
    endif; ?>
    <div class="b-agrebnev-wikiinside__container">
        <div class="b-agrebnev-wikiinside__panel" id="<?= $blockId ?>-menu-target">
        </div>
        <div class="b-agrebnev-wikiinside__content" id="<?= $blockId ?>-body-target">
            <?= Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_BODY__REFRESH_PAGE') ?>
        </div>
    </div>
    <?php
    if ('Y' === $arParams['IFRAME_REQUEST']): ?>
        <div class="b-agrebnev-wikiinside__footer">
            <div class="b-agrebnev-wikiinside__footer__copy">
                <a href="https://www.1c-bitrix.ru/" target="_blank">1c-bitrix.ru</a>
            </div>
            <div class="b-agrebnev-wikiinside__footer__copy">
                <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&tf=1&to=my.grebnev.work@gmail.com"
                   target="_blank"><?= Loc::getMessage(
                        'AGREBNEV_WI_COMPONENT_TMPL_FOOTER__DEVELOPER'
                    ) ?></a>
            </div>
        </div>
    <?php
    endif; ?>
</div>

<script id="<?= $blockId ?>-menu-template" type="text/x-template">
    <ul>
        {{#SECTIONS}}
        <li>
            <a class="b-agrebnev-wikiinside__panel__link b-agrebnev-wikiinside__link-p--lvl-{{DEPTH_LEVEL}} js-agrebnev-wikiinside-sec{{#ACTIVE}} is-active{{/ACTIVE}}"
               href="javascript:void(0);"
               data-id="{{ID}}"
               data-code="{{CODE}}"
            >{{NAME}}</a>
            {{#HAS_SUB_ITEMS}}
            <ul>
                {{#SUB_ITEMS}}
                <li>
                    <a class="b-agrebnev-wikiinside__panel__link b-agrebnev-wikiinside__link-p--lvl-{{DEPTH_LEVEL}} js-agrebnev-wikiinside-sec{{#ACTIVE}} is-active{{/ACTIVE}}"
                       href="javascript:void(0);"
                       data-id="{{ID}}"
                       data-code="{{CODE}}"
                    >{{NAME}}</a>
                    {{#HAS_SUB_ITEMS}}
                    <ul>
                        {{#SUB_ITEMS}}
                        <li>
                            <a class="b-agrebnev-wikiinside__panel__link b-agrebnev-wikiinside__link-p--lvl-{{DEPTH_LEVEL}} js-agrebnev-wikiinside-sec{{#ACTIVE}} is-active{{/ACTIVE}}"
                               href="javascript:void(0);"
                               data-id="{{ID}}"
                               data-ode="{{CODE}}"
                            >{{NAME}}</a>
                            {{#HAS_SUB_ITEMS}}
                            <ul>
                                {{#SUB_ITEMS}}
                                <li>
                                    <a class="b-agrebnev-wikiinside__panel__link b-agrebnev-wikiinside__link-p--lvl-{{DEPTH_LEVEL}} js-agrebnev-wikiinside-sec{{#ACTIVE}} is-active{{/ACTIVE}}"
                                       href="javascript:void(0);"
                                       data-id="{{ID}}"
                                       data-code="{{CODE}}"
                                    >{{NAME}}</a>
                                    {{#HAS_SUB_ITEMS}}
                                    <ul>
                                        {{#SUB_ITEMS}}
                                        <li>
                                            <a class="b-agrebnev-wikiinside__panel__link b-agrebnev-wikiinside__link-p--lvl-{{DEPTH_LEVEL}} js-agrebnev-wikiinside-sec{{#ACTIVE}} is-active{{/ACTIVE}}"
                                               href="javascript:void(0);"
                                               data-id="{{ID}}"
                                               data-code="{{CODE}}"
                                            >{{NAME}}</a>
                                        </li>
                                        {{/SUB_ITEMS}}
                                    </ul>
                                    {{/HAS_SUB_ITEMS}}
                                </li>
                                {{/SUB_ITEMS}}
                            </ul>
                            {{/HAS_SUB_ITEMS}}
                        </li>
                        {{/SUB_ITEMS}}
                    </ul>
                    {{/HAS_SUB_ITEMS}}
                </li>
                {{/SUB_ITEMS}}
            </ul>
            {{/HAS_SUB_ITEMS}}
        </li>
        {{/SECTIONS}}
    </ul>
</script>
<script id="<?= $blockId ?>-body-template" type="text/x-template">
    {{#EDIT_URL}}
    <a
            class="b-agrebnev-wikiinside__content__edit-article "
            href="{{{EDIT_URL}}}"
            target="_blank"
            title="<?= Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_BODY__EDIT_TITLE') ?>"
    ><?= Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_BODY__EDIT') ?></a>
    {{/EDIT_URL}}
    <div class="b-agrebnev-wikiinside__content__body">
        <div class="b-agrebnev-wikiinside__content__title">{{#TITLE}}{{{TITLE}}}{{/TITLE}}</div>
        <div class="b-agrebnev-wikiinside__content__elements-wrap">
            {{#HAS_ELEMENTS}}
            <strong><?= Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_BODY__OTHER_ELEMENTS') ?></strong>
            <ul class="b-agrebnev-wikiinside__content__elements">
                {{#ELEMENTS}}
                <li class="b-agrebnev-wikiinside__content__element">
                    <a
                            class="b-agrebnev-wikiinside__content__link js-agrebnev-wikiinside-elem{{#ACTIVE}} is-active{{/ACTIVE}}"
                            href="javascript:void(0);"
                            data-id="{{ID}}"
                            data-code="{{CODE}}"
                    >{{NAME}}</a>
                </li>
                {{/ELEMENTS}}
            </ul>
        </div>
        {{/HAS_ELEMENTS}}
        <div class="b-agrebnev-wikiinside__content__description">
            {{#DESCRIPTION}}
            {{{DESCRIPTION}}}
            {{/DESCRIPTION}}
            {{^DESCRIPTION}}
            <?= Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_BODY__EMPTY_DESCRIPTION') ?>
            {{/DESCRIPTION}}
        </div>
    </div>
</script>

<?php
$messages = [
    'AGREBNEV_WI_COMPONENT_TMPL_JS__GO_MAIN' => Loc::getMessage('AGREBNEV_WI_COMPONENT_TMPL_JS__GO_MAIN'),
];
?>
<script>
    BX.ready(function () {
        BX.message(<?=\CUtil::PhpToJSObject($messages)?>);

        new AgrebnevWikiInside.Components.Data(
            '<?=$blockId?>',
            <?=Json::encode($arParams)?>,
            <?=Json::encode($arResult)?>,
            {
                componentName: '<?=$component->getName()?>'
            }
        );
    })
</script>
