<?php

namespace Agrebnev\WikiInside;

use Bitrix\Main\Page\Asset;

class EventHandlers
{

    public static function onProlog(): void
    {
        Asset::getInstance()->addJs('/bitrix/modules/agrebnev.wikiinside/js/icon/dist/app.bundle.js');
        Asset::getInstance()->addJs('/bitrix/modules/agrebnev.wikiinside/js/on/dist/app.bundle.js');
    }
}
