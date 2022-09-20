<?

//==================== Класс для работы с инфоблоками ====================

namespace Bitrix\Main\Lyalyaev;

use \Bitrix\Main\Lyalyaev\CKLIBlock;

class CKLIBlockTech {
	
	// Добавление технических полей к массиву данных
	public static function addTechFields($data, &$arROW, $innerPrefix = '') {
		$arROW[$innerPrefix."ACTIVE"]			= $data["ACTIVE"];
		$arROW[$innerPrefix."SORT"]				= $data["SORT"];
		$arROW[$innerPrefix."DATE_ACTIVE_FROM"]	= $data["DATE_ACTIVE_FROM"];
		$arROW[$innerPrefix."DATE_ACTIVE_TO"]	= $data["DATE_ACTIVE_TO"];
	}
	
	// Получение данных элементов инфоблоков
	public static function processData($arData, &$arRes, $iblockID, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		if (count($arData) > 0) {
			foreach ($arData as $data) {
				$arROW = self::getROW($data, $iblockID, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
				$arRes[] = $arROW;
			}
		}
		/*
		else {
			$data = $arData[0];
			$arROW = self::getROW($data, $iblockID, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
			$arRes[] = $arROW;
		}
		*/
	}
	
	// Получение данных инфоблоков по фильтру
	public static function getData($iblockID, $arFilter = NULL, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		$arRes = array();
		if ($arFilter === NULL || !is_array($arFilter)) {
			$arFilter = array();
		}
		$arFilter = array_merge(array("IBLOCK_ID" => $iblockID), $arFilter);
		$arFilter = array_unique($arFilter);
		$arData = CKLIBlock::GetIBlockElementsProperties($arFilter);
		self::processData($arData, $arRes, $iblockID, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
		return $arRes;
	}
	
	// Получение данных инфоблоков по фильтру по полю
	public static function getDataByField($iblockID, $ID, $field = "ID", $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		$arRes = array();
		$arFilter = array(
			"IBLOCK_ID"	=> $iblockID,
			$field		=> $ID
		);
		return self::getData($iblockID, $arFilter, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
	}
	
	// Получение шаблона массива данных для инфоблока
	public static function getROW($data, $iblockID, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		$arPropCodes = CKLIBlock::getIBlockPropertiesCodes($iblockID);
		$arROW = array(
			$innerPrefix."ID"	=> $data["ID"],
			$innerPrefix."NAME"	=> $data["NAME"],
		);
		foreach ($arPropCodes as $propCode) {
			if (!($deleteBlank && !isset($data["PROPERTIES"][$propCode]))) {
				if ($getPropFields) {
					$arROW[$innerPrefix.$prefix.$propCode.'_ENUM'] = $data["PROPERTIES"][$propCode]["VALUE_ENUM"];
					$arROW[$innerPrefix.$prefix.$propCode.'_ENUM_ID'] = $data["PROPERTIES"][$propCode]["VALUE_ENUM_ID"];
					$arROW[$innerPrefix.$prefix.$propCode.'_XML_ID'] = $data["PROPERTIES"][$propCode]["VALUE_XML_ID"];
				}
				$arROW[$innerPrefix.$prefix.$propCode] = $data["PROPERTIES"][$propCode]["VALUE"];
			}
		}
		if ($getTechFields) {
			self::addTechFields($data, $arROW, $innerPrefix);
		}
		return $arROW;
	}
	
}
