<?php

use \Bitrix\Main\EventManager;
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

class agrebnev_wikiinside extends CModule
{

    public $MODULE_ID = 'agrebnev.wikiinside';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_CSS;
    public $MODULE_GROUP_RIGHTS = 'Y';

    private $iblockTypeCode = 'agrebnev_wikiinside';

    private $errors = [];

    public function __construct()
    {
        $arModuleVersion = [];

        include(dirname(__FILE__) . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage('AGREBNEV_WI_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('AGREBNEV_WI_INSTALL_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('AGREBNEV_WI_INSTALL_COPMPANY_NAME');
        $this->PARTNER_URI = 'https://agrebnev.ru/';
    }

    public function InstallDB($install_wizard = true)
    {
        Option::set($this->MODULE_ID, 'componentCacheTime', (3600 * 24 * 7));

        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandler(
            'main',
            'OnProlog',
            $this->MODULE_ID,
            'Agrebnev\WikiInside\EventHandlers',
            'onProlog',
            50
        );

        return true;
    }

    public function UnInstallDB($arParams = array())
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'main',
            'OnProlog',
            $this->MODULE_ID,
            'Agrebnev\WikiInside\EventHandlers',
            'onProlog'
        );

        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/modules/agrebnev.wikiinside/install/components',
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/components',
            true,
            true
        );
        CopyDirFiles(
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/modules/agrebnev.wikiinside/install/admin',
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/admin'
        );
        CopyDirFiles(
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/modules/agrebnev.wikiinside/install/js',
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/js',
            true,
            true
        );
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles(
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/modules/agrebnev.wikiinside/install/components/',
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/components'
        );
        DeleteDirFiles(
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/modules/agrebnev.wikiinside/install/admin/',
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/admin'
        );
        DeleteDirFiles(
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/modules/agrebnev.wikiinside/install/js/',
            \Bitrix\Main\Application::getDocumentRoot() . '/bitrix/js'
        );
    }

    public function InstallIBlock()
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        /** @global CDatabase $DB */
        global $DB;

        $siteIds = [];

        // site
        {
            $iterator = \Bitrix\Main\SiteTable::getList([
                'select' => [
                    'LID',
                ],
                'filter' => [
                    'ACTIVE' => 'Y',
                ],
            ]);
            while ($row = $iterator->fetch()) {
                $siteIds[] = $row['LID'];
            }
        }

        // type
        {
            $fields = [
                'ID' => $this->iblockTypeCode,
                'SECTIONS' => 'Y',
                'IN_RSS' => 'N',
                'SORT' => 100000,
                'LANG' => [
                    'en' => [
                        'NAME' => 'WikiInside',
                        'SECTION_NAME' => 'Sections',
                        'ELEMENT_NAME' => 'Elements'
                    ],
                    'ru' => [
                        'NAME' => 'WikiInside',
                        'SECTION_NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__IBLOCK_TYPE__SECTION_NAME'),
                        'ELEMENT_NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__IBLOCK_TYPE__ELEMENT_NAME'),
                    ],
                ],
            ];
            $iblocktype = new \CIBlockType();
            $DB->StartTransaction();
            $result = $iblocktype->Add($fields);
            if (false === $result) {
                $DB->Rollback();
                $this->errors[] = $iblocktype->LAST_ERROR;
                return;
            }

            $DB->Commit();
        }

        // iblock
        {
            $iblock = new \CIBlock();

            $fields = [
                'ACTIVE' => 'Y',
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__IBLOCK__NAME'),
                'CODE' => 'AGREBNEV_WI_WIKIINSIDE_DATA',
                'API_CODE' => 'apiagrebnevwikiinsidedata',
                'LIST_PAGE_URL' => '',
                'DETAIL_PAGE_URL' => '',
                'IBLOCK_TYPE_ID' => $this->iblockTypeCode,
                'SITE_ID' => $siteIds,
                'SORT' => 1,
                'GROUP_ID' => [2 => 'R'],
                'INDEX_SECTION' => 'N',
                'INDEX_ELEMENT' => 'N',
                'DESCRIPTION_TYPE' => 'html',
                'DESCRIPTION' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__IBLOCK__DESCRIPTION'),

            ];
            $iblockId = $iblock->Add($fields);
            if (0 === (int)$iblockId) {
                $this->errors[] = $iblock->LAST_ERROR;
                return;
            }

            Option::set($this->MODULE_ID, 'iblockId', $iblockId);

            $fields = [
                'ACTIVE_FROM' => [
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => '=now',
                ],
                'CODE' => [
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => [
                        'UNIQUE' => 'Y',
                        'TRANSLITERATION' => 'Y',
                    ],
                ],
                'SECTION_DESCRIPTION_TYPE' => [
                    'DEFAULT_VALUE' => 'html',
                ],
                'SECTION_DESCRIPTION_TYPE_ALLOW_CHANGE' => [
                    'DEFAULT_VALUE' => 'Y',
                ],
                'SECTION_CODE' => [
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => [
                        'UNIQUE' => 'Y',
                        'TRANSLITERATION' => 'Y',
                    ],
                ],
            ];
            \CIBlock::SetFields($iblockId, $fields);
        }

        // properties
        {
            $propIdByCode = [];

            $properties = [
                [
                    'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__IBLOCK__PROPERTY_LOCATION_PATHNAME'),
                    'HINT' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__IBLOCK__PROPERTY_LOCATION_PATHNAME__HINT'),
                    'ACTIVE' => 'Y',
                    'SORT' => 100,
                    'CODE' => 'LOCATION_PATHNAME',
                    'PROPERTY_TYPE' => 'S',
                    'IBLOCK_ID' => $iblockId,
                    'WITH_DESCRIPTION' => 'N',
                    'MULTIPLE' => 'N',
                ],
                [
                    'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__IBLOCK__PROPERTY_LOCATION_SEARCH'),
                    'HINT' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__IBLOCK__PROPERTY_LOCATION_SEARCH__HINT'),
                    'ACTIVE' => 'Y',
                    'SORT' => 100,
                    'CODE' => 'LOCATION_SEARCH',
                    'PROPERTY_TYPE' => 'S',
                    'IBLOCK_ID' => $iblockId,
                    'WITH_DESCRIPTION' => 'Y',
                    'MULTIPLE' => 'Y',
                    'MULTIPLE_CNT' => 15,
                ],
            ];

            foreach ($properties as $fields) {
                $iblockProperty = new \CIBlockProperty();
                $propertyId = $iblockProperty->Add($fields);
                if (0 === (int)$propertyId) {
                    $this->errors[] = $iblockProperty->LAST_ERROR;
                    continue;
                }

                $propIdByCode[$fields['CODE']] = $propertyId;

                Option::set($this->MODULE_ID, 'iblockPropertyId_' . $fields['CODE'], $propertyId);
            }
        }

        // sections
        {
            $defaultFields = [
                'IBLOCK_ID' => $iblockId,
                'ACTIVE' => 'Y',
                'SORT' => 500,
                'DESCRIPTION_TYPE' => 'text',
            ];

            $wikiinsideSection = [
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__WI_SECTION__NAME'),
                'CODE' => 'wi-section',
                'SORT' => 100,
                'DESCRIPTION' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__WI_SECTION__DESCRIPTION'),
            ];

            $baseSection = [
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__MAIN_SECTION__NAME'),
                'CODE' => 'base-info',
                'SORT' => 200,
                'DESCRIPTION' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__MAIN_SECTION__DESCRIPTION'),
            ];

            $mainSubSection1 = [
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__SUB_SECTION_1__NAME'),
                'CODE' => 'main-sub-section-1',
                'DESCRIPTION' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__SUB_SECTION_1__DESCRIPTION'),
                'IBLOCK_SECTION_ID' => $baseSection['CODE'],
            ];

            $iblockSection = [
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__IBLOCK_SECTION__NAME'),
                'CODE' => 'iblock-section',
                'SORT' => 300,
                'DESCRIPTION' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__IBLOCK_SECTION__DESCRIPTION'),
            ];

            $sections = [
                $wikiinsideSection,
                $baseSection,
                $mainSubSection1,
                $iblockSection,
            ];

            $sectionIdsByCode = [];
            $iblockSection = new \CIBlockSection();
            foreach ($sections as $fields) {
                $fields = array_merge($defaultFields, $fields);

                if (true === array_key_exists($fields['IBLOCK_SECTION_ID'], $sectionIdsByCode)) {
                    $fields['IBLOCK_SECTION_ID'] = (int)$sectionIdsByCode[$fields['IBLOCK_SECTION_ID']];
                    if (0 === $fields['IBLOCK_SECTION_ID']) {
                        continue;
                    }
                }

                $id = (int)$iblockSection->Add($fields);
                if (0 === $id) {
                    $this->errors[] = $iblockSection->LAST_ERROR;
                    continue;
                }

                $sectionIdsByCode[$fields['CODE']] = $id;
            }
        }

        // elements
        {
            $defaultFields = [
                'MODIFIED_BY' => (int)\Bitrix\Main\Engine\CurrentUser::get()->getId(),
                'IBLOCK_ID' => $iblockId,
                'IBLOCK_SECTION_ID' => false,
                'IBLOCK_SECTION' => [],
                'ACTIVE' => 'Y',
                'ACTIVE_FROM' => ConvertTimeStamp(time(), 'FULL'),
                'SORT' => 500,
                'PREVIEW_TEXT_TYPE' => 'html',
                'PREVIEW_TEXT' => '',
                'DETAIL_TEXT_TYPE' => 'html',
                'DETAIL_TEXT' => '',
            ];

            $elementWikiInsideAdd = [
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT__WI_ADD__NAME'),
                'CODE' => 'element-wikiinside-add',
                'IBLOCK_SECTION_ID' => 'wi-section',
                'SORT' => 100,
                'DETAIL_TEXT' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT__WI_ADD__DETAIL_TEXT'),
            ];

            $elementWikiInsideEdit = [
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT__WI_EDIT__NAME'),
                'CODE' => 'element-wikiinside-edit',
                'IBLOCK_SECTION_ID' => 'wi-section',
                'SORT' => 100,
                'DETAIL_TEXT' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT__WI_EDIT__DETAIL_TEXT'),
            ];

            $elementWikiInsideSettings = [
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT__WI_SETTINGS__NAME'),
                'CODE' => 'element-wikiinside-settings',
                'IBLOCK_SECTION_ID' => 'wi-section',
                'SORT' => 200,
                'DETAIL_TEXT' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT__WI_SETTINGS__DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    $propIdByCode['LOCATION_PATHNAME'] => '/bitrix/admin/settings.php',
                    $propIdByCode['LOCATION_SEARCH'] => [
                        'n0' => [
                            'VALUE' => 'mid',
                            'DESCRIPTION' => $this->MODULE_ID,
                        ],
                    ],
                ],
            ];

            $elementMainSettings = [
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT_3__NAME'),
                'CODE' => 'element-main-settings',
                'IBLOCK_SECTION_ID' => 'main-sub-section-1',
                'DETAIL_TEXT' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT_3__DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    $propIdByCode['LOCATION_PATHNAME'] => '/bitrix/admin/settings.php',
                    $propIdByCode['LOCATION_SEARCH'] => [
                        'n0' => [
                            'VALUE' => 'mid',
                            'DESCRIPTION' => 'main',
                        ],
                    ],
                ],
            ];

            $elementIblockSettings1 = [
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT_4__NAME'),
                'CODE' => 'element-iblock-1',
                'IBLOCK_SECTION_ID' => 'iblock-section',
                'DETAIL_TEXT' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT_4__DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    $propIdByCode['LOCATION_PATHNAME'] => '/bitrix/admin/settings.php',
                    $propIdByCode['LOCATION_SEARCH'] => [
                        'n0' => [
                            'VALUE' => 'mid',
                            'DESCRIPTION' => 'iblock',
                        ],
                    ],
                ],
            ];

            $elementIblockSettings2 = [
                'NAME' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT_5__NAME'),
                'CODE' => 'element-iblock-2',
                'IBLOCK_SECTION_ID' => 'iblock-section',
                'DETAIL_TEXT' => Loc::getMessage('AGREBNEV_WI_INSTALL_DEMODATA__ELEMENT_5__DETAIL_TEXT'),
                'PROPERTY_VALUES' => [
                    $propIdByCode['LOCATION_PATHNAME'] => '/bitrix/admin/settings.php',
                    $propIdByCode['LOCATION_SEARCH'] => [
                        'n0' => [
                            'VALUE' => 'mid',
                            'DESCRIPTION' => 'iblock',
                        ],
                    ],
                ],
            ];

            $elements = [
                $elementWikiInsideAdd,
                $elementWikiInsideEdit,
                $elementWikiInsideSettings,
                $elementMainSettings,
                $elementIblockSettings1,
                $elementIblockSettings2,
            ];

            $elementIdsByCode = [];
            $iblockElement = new \CIBlockElement();
            foreach ($elements as $fields) {
                $fields = array_merge($defaultFields, $fields);

                if (true === array_key_exists($fields['IBLOCK_SECTION_ID'], $sectionIdsByCode)) {
                    $sectionId = (int)$sectionIdsByCode[$fields['IBLOCK_SECTION_ID']];
                    $fields['IBLOCK_SECTION_ID'] = $sectionId;
                    $fields['IBLOCK_SECTION'] = [$sectionId];
                } else {
                    $fields['IBLOCK_SECTION_ID'] = false;
                }

                $elementId = (int)$iblockElement->Add($fields);
                if (0 === $elementId) {
                    $this->errors[] = $iblockElement->LAST_ERROR;
                    continue;
                }

                $elementIdsByCode[$fields['CODE']] = $elementId;
            }
        }
    }

    public function UnInstallIBlock()
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        $iblockId = (int)Option::get($this->MODULE_ID, 'iblockId');
        if (0 < $iblockId) {
            \CIBlock::delete($iblockId);
        }

        \CIBlockType::Delete($this->iblockTypeCode);
    }

    public function DoInstall()
    {
        global $APPLICATION, $errors;

        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallDB(false);
        $this->InstallFiles();
        $this->InstallIBlock();

        if (0 < count($this->errors)) {
            $errors = implode('<br>', $this->errors);

            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('CATALOG_INSTALL_TITLE'),
                $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/catalog/install/step1.php"
            );

            return false;
        }

        return true;
    }

    public function DoUninstall()
    {
        $this->UnInstallIBlock();
        $this->UnInstallFiles();
        $this->UnInstallDB();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        return true;
    }
}
