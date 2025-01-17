<?php

namespace Agrebnev\WikiInside;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;

class EventHandlers
{

    public static function onProlog(): void
    {
        $iblockId = (int)Option::get(Utils::getModuleId(), 'iblockId');

        Asset::getInstance()->addJs('/bitrix/js/agrebnev.wikiinside/icon/dist/app.bundle.js');
        Asset::getInstance()->addJs('/bitrix/js/agrebnev.wikiinside/on/dist/app.bundle.js');

        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        if (
            $iblockId !== (int)$request->getQuery('IBLOCK_ID')
            || 'addnew' !== (string)$request->getQuery('agrebnev_wi_action')
        ) {
            return;
        }

        $propIdPathname = (int)Option::get(Utils::getModuleId(), 'iblockPropertyId_LOCATION_PATHNAME');
        $propIdSearch = (int)Option::get(Utils::getModuleId(), 'iblockPropertyId_LOCATION_SEARCH');

        {
            $pathname = (string)$request->getQuery('agrebnev_wi_params')['location']['pathname'];
            $pathname = urldecode($pathname);

            $search = (string)$request->getQuery('agrebnev_wi_params')['location']['search'];
            $search = str_replace('?', '', $search);
            $search = urldecode($search);
        }

        $messages = [
            'AGREBNEV_WI_OPTIONS_IBLOCK_ID' => $iblockId,
            'AGREBNEV_WI_OPTIONS_PROP_ID_PATHNAME' => $propIdPathname,
            'AGREBNEV_WI_OPTIONS_PROP_ID_SEARCH' => $propIdSearch,
            'AGREBNEV_WI_ADD_PARAMS_LOCATION_PATHNAME' => $pathname,
            'AGREBNEV_WI_ADD_PARAMS_LOCATION_SEARCH' => $search,
        ];

        Asset::getInstance()->addString(
            '<script>BX.ready(function () { BX.message(' . \CUtil::PhpToJSObject($messages) . ' );});</script>'
        );
        Asset::getInstance()->addJs('/bitrix/js/agrebnev.wikiinside/addnew/dist/app.bundle.js');
    }
}
