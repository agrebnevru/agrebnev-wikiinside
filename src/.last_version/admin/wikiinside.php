<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Agrebnev\WikiInside\Utils;

if (!defined('NO_AGENT_CHECK')) {
    define('NO_AGENT_CHECK', true);
}

/**
 * @global CMain $APPLICATION
 */

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/iblock/prolog.php");

$mid = Utils::getModuleId();

Loader::includeModule($mid);
Loc::loadMessages(__FILE__);

// ajax part

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_admin_after.php");

$APPLICATION->SetTitle('Wiki Inside');

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$componentParams = [
    'IFRAME_REQUEST' => 'N',
    'LOCATION_PATHNAME' => '',
    'LOCATION_SEARCH' => '',
];

if ('Y' === $request->getQuery('IFRAME')) {
    $pathname = (string)$request->getQuery('location')['pathname'];

    $searchData = [];
    $search = (string)$request->getQuery('location')['search'];

    $componentParams['IFRAME_REQUEST'] = 'Y';
    $componentParams['LOCATION_PATHNAME'] = $pathname;
    $componentParams['LOCATION_SEARCH'] = $search;
}

?>

<?php
$APPLICATION->IncludeComponent(
    'agrebnev:wikiinside.data',
    '',
    $componentParams
); ?>

<?php

require($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_admin.php");
