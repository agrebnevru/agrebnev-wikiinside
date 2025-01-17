<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Config\Option;
use Agrebnev\WikiInside\Utils;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 */
class AgrebnevWikiInsideDataComponent extends \CBitrixComponent
{

    private $iblockId = 0;
    private $componentCacheTime = 0;

    private $entityDataClass = null;

    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->iblockId = (int)Option::get(Utils::getModuleId(), 'iblockId');
        $this->componentCacheTime = (int)Option::get(Utils::getModuleId(), 'componentCacheTime');

        if (0 === $this->iblockId) {
            return;
        }

        $this->entityDataClass = \Bitrix\Iblock\Iblock::wakeUp($this->iblockId)->getEntityDataClass();
        if (!$this->entityDataClass) {
            throw new LoaderException('IBlock with data not installed');
        }
    }

    public function onPrepareComponentParams($arParams): array
    {
        $arParams['IBLOCK_ID'] = $this->iblockId;
        $this->arResult['ORIGINAL_PARAMS'] = $arParams;

        // echo "\$arParams =<br><textarea>";
        // print_r($arParams);
        // echo "</textarea><br>";

        $params = [
            'IBLOCK_ID=' . $arParams['IBLOCK_ID'],
            'type=agrebnev_wikiinside',
            'lang=' . LANGUAGE_ID,
            'find_section_section=0',
            'SECTION_ID=0',
            'apply_filter=Y',
        ];
        $this->arResult['BASE_URL'] = '/bitrix/admin/iblock_list_admin.php?' . implode('&', $params);

        $params = [
            'IBLOCK_ID=' . $arParams['IBLOCK_ID'],
            'type=agrebnev_wikiinside',
            'lang=' . LANGUAGE_ID,
            'agrebnev_wi_action=addnew',
            'agrebnev_wi_params[location][pathname]=' . (string)urlencode($arParams['LOCATION_PATHNAME']),
            'agrebnev_wi_params[location][search]=' . (string)urlencode($arParams['LOCATION_SEARCH']),
        ];
        $this->arResult['ADD_URL'] = '/bitrix/admin/iblock_element_edit.php?' . implode('&', $params);

        $params = [
            'IBLOCK_ID=' . $arParams['IBLOCK_ID'],
            'type=agrebnev_wikiinside',
            'lang=' . LANGUAGE_ID,
            'ID=',
        ];

        return $arParams;
    }

    protected function fillResult(): void
    {
        $this->arResult['JS_DATA'] = [];

        $this->fillIblock();
        $this->fillSections();
        $this->fillElements();
        $this->fillNavigation();
        $this->fillCurrentData();
    }

    private function fillIblock(): void
    {
        $this->arResult['JS_DATA']['IBLOCK'] = [];
        $this->data['IBLOCK'] = [];

        $iterator = \Bitrix\Iblock\IblockTable::getList([
            'select' => [
                'ID',
                'NAME',
                'CODE',
                'DESCRIPTION_TYPE',
                'DESCRIPTION',
            ],
            'filter' => [
                'ID' => $this->iblockId,
            ],
            'cache' => [
                'ttl' => $this->componentCacheTime,
            ],
            'limit' => 1,
        ]);
        if ($row = $iterator->fetch()) {
            $row['EDIT_URL'] = '/bitrix/admin/iblock_edit.php?ID=' . $row['ID'] . '&type=agrebnev_wikiinside&lang=' . LANGUAGE_ID . '&admin=Y';

            $this->data['IBLOCK'] = $row;
        }

        $this->arResult['JS_DATA']['IBLOCK'] = $this->data['IBLOCK'];
    }

    private function fillSections(): void
    {
        $this->arResult['JS_DATA']['SECTIONS'] = [];
        $this->data['SECTIONS'] = [];

        $iterator = \Bitrix\Iblock\SectionTable::getList([
            'order' => [
                'LEFT_MARGIN' => 'ASC',
                'SORT' => 'ASC',
                // 'DEPTH_LEVEL' => 'ASC',
            ],
            'select' => [
                'ID',
                'NAME',
                'CODE',
                'SORT',
                'DEPTH_LEVEL',
                'LEFT_MARGIN',
                'RIGHT_MARGIN',
                'DESCRIPTION_TYPE',
                'DESCRIPTION',
            ],
            'filter' => [
                'IBLOCK_ID' => $this->iblockId,
                'ACTIVE' => 'Y',
            ],
            'cache' => [
                'ttl' => $this->componentCacheTime,
            ],
        ]);
        while ($row = $iterator->fetch()) {
            $row['EDIT_URL'] = '/bitrix/admin/iblock_section_edit.php?IBLOCK_ID=' . $this->iblockId . '&type=agrebnev_wikiinside&lang=' . LANGUAGE_ID . '&ID=' . $row['ID'];

            $this->data['SECTIONS'][] = $row;
        }

        $this->arResult['JS_DATA']['SECTIONS'] = $this->data['SECTIONS'];
    }

    private function fillElements(): void
    {
        $this->arResult['JS_DATA']['ITEMS'] = [];
        $this->data['ITEMS'] = [];

        if (!$this->entityDataClass) {
            return;
        }

        $iterator = $this->entityDataClass::getList([
            'order' => [
                'SORT' => 'ASC',
            ],
            'select' => [
                'ID',
                'IBLOCK_SECTION_ID',
                'NAME',
                'CODE',
                'LOCATION_PATHNAME',
                'LOCATION_SEARCH',
                'LOCATION_SEARCH',
                'DETAIL_TEXT',
            ],
            'filter' => [
                'ACTIVE' => 'Y',
            ],
            'cache' => [
                'ttl' => $this->componentCacheTime,
            ],
        ]);
        while ($object = $iterator->fetchObject()) {
            $row = [
                'ID' => $object->getId(),
                'IBLOCK_SECTION_ID' => $object->getIblockSectionId(),
                'NAME' => $object->getName(),
                'CODE' => $object->getCode(),
                'DETAIL_TEXT' => $object->getDetailText(),
                'PATHNAME' => '',
                'SEARCH' => [],
            ];
            $row['EDIT_URL'] = '/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=' . $this->iblockId . '&type=agrebnev_wikiinside&lang=' . LANGUAGE_ID . '&ID=' . $row['ID'];

            $property = $object->getLocationPathname();
            if ($property) {
                $row['PATHNAME'] = $property->getValue();
            }

            $property = $object->getLocationSearch();
            if ($property) {
                foreach ($property->getAll() as $value) {
                    $row['SEARCH'][$value->getValue()] = $value->getDescription();
                }
            }

            $this->data['ITEMS'][] = $row;
        }

        $this->arResult['JS_DATA']['ITEMS'] = $this->data['ITEMS'];
    }

    private function fillNavigation(): void
    {
        $this->arResult['JS_DATA']['NAVIGATION'] = self::recursiveAlignItems($this->arResult['JS_DATA']['SECTIONS']);
    }

    private function fillCurrentData(): void
    {
        $this->arResult['JS_DATA']['CURRENT_DATA'] = [
            'DESCRIPTION' => '',
        ];
    }

    public function executeComponent()
    {
        $this->checkModules();
        $this->fillResult();

        $this->includeComponentTemplate();
    }

    protected function checkModules(): void
    {
        $needModules = ['agrebnev.wikiinside'];
        foreach ($needModules as $module) {
            if (false === Loader::includeModule($module)) {
                throw new LoaderException('Module ' . strtoupper($module) . ' not installed');
            }
        }
    }

    public static function recursiveAlignItems(&$arItems, $level = 1, &$i = 0): array
    {
        $returnArray = array();

        if (!is_array($arItems)) {
            return $returnArray;
        }

        for (
            $currentItemKey = 0, $countItems = count($arItems);
            $i < $countItems;
            ++$i
        ) {
            $arItem = $arItems[$i];

            if ($arItem['DEPTH_LEVEL'] == $level) {
                $currKey = $currentItemKey++;
                $returnArray[$currKey] = $arItem;
                $returnArray[$currKey]['HAS_SUB_ITEMS'] = false;
            } elseif ($arItem['DEPTH_LEVEL'] > $level) {
                $currKey = $currentItemKey - 1;
                $returnArray[$currKey]['HAS_SUB_ITEMS'] = true;
                $returnArray[$currKey]['SUB_ITEMS'] = self::recursiveAlignItems(
                    $arItems,
                    $level + 1,
                    $i
                );
            } elseif ($level > $arItem['DEPTH_LEVEL']) {
                --$i;
                break;
            }
        }

        return $returnArray;
    }
}
