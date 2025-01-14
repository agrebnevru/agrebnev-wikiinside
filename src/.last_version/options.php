<?php

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @global string $mid
 */

Loc::loadMessages(__FILE__);

$request = Application::getInstance()->getContext()->getRequest();

$tabs = [];
$tabs[] = [
    'DIV' => 'agrebnev_wikiinside_tab_settings',
    'TAB' => Loc::getMessage('AGREBNEV_WI_TAB_NAME'),
    'ICON' => '',
    'TITLE' => Loc::getMessage('AGREBNEV_WI_TAB_TITLE'),
];

$allOptions = [];

/************************ tab ***************************/
$allOptions['agrebnev_wikiinside_tab_settings'][] = Loc::getMessage('AGREBNEV_WI_OPTIONS_BLOCK_NAME_BASE');
$allOptions['agrebnev_wikiinside_tab_settings'][] = [
    'iblockId',
    Loc::getMessage('AGREBNEV_WI_OPTIONS_IBLOCK_ID'),
    null,
    ['statichtml']
];
$allOptions['agrebnev_wikiinside_tab_settings'][] = [
    'iblockPropertyId_LOCATION_PATHNAME',
    Loc::getMessage('AGREBNEV_WI_OPTIONS_IBLOCK_PROP_ID__LOCATION_PATHNAME'),
    null,
    ['statichtml']
];
$allOptions['agrebnev_wikiinside_tab_settings'][] = [
    'iblockPropertyId_LOCATION_SEARCH',
    Loc::getMessage('AGREBNEV_WI_OPTIONS_IBLOCK_PROP_ID__LOCATION_SEARCH'),
    null,
    ['statichtml']
];
$allOptions['agrebnev_wikiinside_tab_settings'][] = Loc::getMessage('AGREBNEV_WI_OPTIONS_BLOCK_NAME_COMPONENT');
$allOptions['agrebnev_wikiinside_tab_settings'][] = [
    'componentCacheTime',
    Loc::getMessage('AGREBNEV_WI_OPTIONS_COMPONENT_CACHE_TIME'),
    null,
    ['text']
];
/************************ tab ***************************/

if (
    (isset($_REQUEST['save']) || isset($_REQUEST['apply']))
    && check_bitrix_sessid()
) {
    __AdmSettingsSaveOptions($mid, $allOptions['agrebnev_wikiinside_tab_settings']);

    LocalRedirect('settings.php?mid=' . $mid . '&lang=' . LANG);
}

$tabControl = new CAdminTabControl('tabControl', $tabs);

?>
<form method="post" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($mid) ?>&amp;lang=<?= LANGUAGE_ID ?>"
      name="agrebnev_wi_settings"><?php
    echo bitrix_sessid_post();

    $tabControl->Begin();

    $tabControl->BeginNextTab();
    __AdmSettingsDrawList($mid, $allOptions['agrebnev_wikiinside_tab_settings']);

    $tabControl->Buttons([]);
    $tabControl->End();

    ?></form>
