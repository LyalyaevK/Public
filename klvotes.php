<?

//==================== Класс для работы с опросами ====================

namespace Bitrix\Main\Lyalyaev;

use \Bitrix\Main\Loader;
use \Bitrix\Highloadblock as HL; 
use \Bitrix\Main\Entity;

use \Bitrix\Main\Lyalyaev\CLyalyaev;
use \Bitrix\Main\Lyalyaev\CKLIBlock;
use \Bitrix\Main\Lyalyaev\CKLIBlockTech;
use \Bitrix\Main\Lyalyaev\CKLBizproc;
use \Bitrix\Main\Lyalyaev\CKLAdminFunctions;
use \Bitrix\Main\Lyalyaev\CIBlockSection;



$arFilter = array("CODE" => "departments");
$iblockDepartmentsID = CKLIBlock::getIBlockIDByCode($arFilter);

$arFilter = array("CODE" => "votes_s1");
$iblockVotesID = CKLIBlock::getIBlockIDByCode($arFilter);

$arFilter = array("CODE" => "vote_variants_s1");
$iblockVoteVariantsID = CKLIBlock::getIBlockIDByCode($arFilter);

$arFilter = array("CODE" => "positions_s1");
$iblockPositionsID = CKLIBlock::getIBlockIDByCode($arFilter);

$arFilter = array("CODE" => "vote_answers_s1");
$iblockVoteAnswersID = CKLIBlock::getIBlockIDByCode($arFilter);



class CKLVotes {
	
	
	//================================================================================
	
	
	// Получение массива данных для инфоблока голосований
	public static function getVotesData($arFilter = NULL, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		GLOBAL $iblockVotesID;
		$iblockID = $iblockVotesID;
		return CKLIBlockTech::getData($iblockID, $arFilter, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
	}
	public static function getVotesDataByField($ID, $field = "ID", $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		GLOBAL $iblockVotesID;
		$iblockID = $iblockVotesID;
		return CKLIBlockTech::getDataByField($iblockID, $ID, $field, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
	}
	public static function getVotesDataByID($ID, $getTechFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		$arRes = self::getVotesDataByField($ID, "ID", $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
		return (count($arRes) > 0) ? $arRes[0] : NULL;
	}
	
	
	// Получение массива данных для инфоблока вариантов голосований
	public static function getVoteVariantsData($arFilter = NULL, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		GLOBAL $iblockVoteVariantsID;
		$iblockID = $iblockVoteVariantsID;
		return CKLIBlockTech::getData($iblockID, $arFilter, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
	}
	public static function getVoteVariantsDataByField($ID, $field = "ID", $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		GLOBAL $iblockVoteVariantsID;
		$iblockID = $iblockVoteVariantsID;
		return CKLIBlockTech::getDataByField($iblockID, $ID, $field, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
	}
	public static function getVoteVariantsDataByID($ID, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		$arRes = self::getVoteVariantsDataByField($ID, "ID", $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
		return (count($arRes) > 0) ? $arRes[0] : NULL;
	}
	
	
	// Получение массива данных для инфоблока позиций голосований
	public static function getPositionsData($arFilter = NULL, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		GLOBAL $iblockPositionsID;
		$iblockID = $iblockPositionsID;
		return CKLIBlockTech::getData($iblockID, $arFilter, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
	}
	public static function getPositionsDataByField($ID, $field = "ID", $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		GLOBAL $iblockPositionsID;
		$iblockID = $iblockPositionsID;
		return CKLIBlockTech::getDataByField($iblockID, $ID, $field, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
	}
	public static function getPositionsDataByID($ID, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		$arRes = self::getPositionsDataByField($ID, "ID", $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
		return (count($arRes) > 0) ? $arRes[0] : NULL;
	}
	
	
	// Получение массива данных для инфоблока ответов для голосований
	public static function getVoteAnswersData($arFilter = NULL, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		GLOBAL $iblockVoteAnswersID;
		$iblockID = $iblockVoteAnswersID;
		return CKLIBlockTech::getData($iblockID, $arFilter, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
	}
	public static function getVoteAnswersDataByField($ID, $field = "ID", $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		GLOBAL $iblockVoteAnswersID;
		$iblockID = $iblockVoteAnswersID;
		return CKLIBlockTech::getDataByField($iblockID, $ID, $field, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
	}
	public static function getVoteAnswersDataByID($ID, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '') {
		$arRes = self::getVoteAnswersDataByField($ID, "ID", $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
		return (count($arRes) > 0) ? $arRes[0] : NULL;
	}
	
	
	
	public static function getVoteAnswersFullData($arFilter = NULL, $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '', $prefixPositions = 'POS_', $prefixVariants = 'VAR_') {
		GLOBAL $iblockVoteAnswersID, $iblockPositionsID, $iblockVoteVariantsID;
		$iblockID = $iblockVoteAnswersID;
		$fieldPositions = $prefix.'POSITION';
		$fieldVariants = $prefix.'VARIANT';
		$arRes = CKLIBlockTech::getData($iblockID, $arFilter, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
		foreach ($arRes as $key => $data) {
			// Позиции
			$arDataP = CKLIBlockTech::getDataByField($iblockPositionsID, $data[$fieldPositions], "ID", $getTechFields, $getPropFields, $deleteBlank, $prefix, $prefixPositions);
			// Варианты ответов
			$arDataV = CKLIBlockTech::getDataByField($iblockVoteVariantsID, $data[$fieldVariants], "ID", $getTechFields, $getPropFields, $deleteBlank, $prefix, $prefixVariants);
			$arRes[$key] = array_merge($data, $arDataP[0], $arDataV[0]);
		}
		return $arRes;
	}
	public static function getVoteAnswersFullDataByField($ID, $field = "ID", $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '', $prefixPositions = 'POS_', $prefixVariants = 'VAR_') {
		GLOBAL $iblockVoteAnswersID, $iblockPositionsID, $iblockVoteVariantsID;
		$iblockID = $iblockVoteAnswersID;
		$fieldPositions = $prefix.'POSITION';
		$fieldVariants = $prefix.'VARIANT';
		$arRes = CKLIBlockTech::getDataByField($iblockID, $ID, $field, $getTechFields, $getPropFields, $deleteBlank, $prefix, $innerPrefix);
		foreach ($arRes as $key => $data) {
			// Позиции
			$arData = CKLIBlockTech::getDataByField($iblockPositionsID, $data[$fieldPositions], "ID", $getTechFields, $getPropFields, $deleteBlank, $prefix, $prefixPositions);
			$arRes[$key] = array_merge($data, $arData[0]);
			// Варианты ответов
			$arData = CKLIBlockTech::getDataByField($iblockVoteVariantsID, $data[$fieldVariants], "ID", $getTechFields, $getPropFields, $deleteBlank, $prefix, $prefixVariants);
			$arRes[$key] = array_merge($data, $arData[0]);
		}
		return $arRes;
	}
	public static function getVoteAnswersFullDataByID($ID, $field = "ID", $getTechFields = false, $getPropFields = false, $deleteBlank = false, $prefix = 'PROP_', $innerPrefix = '', $prefixPositions = 'POS_', $prefixVariants = 'VAR_') {
		$arRes = self::getVoteAnswersFullDataByField($ID, "ID", $getTechFields, $getPropFields, $deleteBlank, $prefix, $prefixPositions, $prefixVariants);
		return (count($arRes) > 0) ? $arRes[0] : NULL;
	}
	
	
	//================================================================================
	
	
	public static function getVoteAddName($ID) {
		$arData = self::getVotesDataByID($ID);
		$voteID = $arData["PROP_PARENT"];
		$arVoteData = self::getVotesDataByID($voteID);
		$res = $arVoteData["NAME"];
		return $res;
	}
	
	public static function getPositionAddName($ID, $full = false) {
		$arData = self::getPositionsDataByID($ID);
		$voteID = $arData["PROP_VOTE"];
		$arVoteData = self::getVotesDataByID($voteID);
		$res = (strlen($arData["PROP_SHOW_NAME"]) > 0) ? $arData["PROP_SHOW_NAME"].' '.$arVoteData["NAME"] : $arVoteData["NAME"];
		if ($full) {
			$addName = self::getVoteAddName($voteID);
			$res = (strlen($res) > 0) ? $res.' '.$addName : $addName;
		}
		return $res;
	}
	
	public static function getVoteVariantAddName($ID, $full = false) {
		$arData = self::getVoteVariantsDataByID($ID);
		$voteID = $arData["PROP_VOTE"];
		$arVoteData = self::getVotesDataByID($voteID);
		$res = $arVoteData["NAME"];
		if ($full) {
			$addName = self::getVoteAddName($voteID);
			$res = (strlen($res) > 0) ? $res.' '.$addName : $addName;
		}
		return $res;
	}
	
	public static function getArVoteAnswerName($ID) {
		$arData = self::getVoteAnswersDataByID($ID);
		$positionID = $arData["PROP_POSITION"];
		$variantID = $arData["PROP_VARIANT"];
		$userID = $arData["PROP_USER"];
		$arPositionData = self::getPositionsDataByID($positionID);
		$positionName = $arPositionData["NAME"];
		$arVariantData = self::getVoteVariantsDataByID($variantID);
		$variantName = $arVariantData["NAME"];
		$arUserData = \CUser::GetByID($userID)->fetch();
		$userName = $arUserData["LAST_NAME"].' '.$arUserData["NAME"];
		$arRes = compact('positionName', 'variantName', 'userName');
		return $arRes;
	}
	
	
	//================================================================================
	
	
	public static function addVoteAnswer($positionID, $variantID, $userID) {
		GLOBAL $iblockVoteAnswersID;
		$arFilter = array(
			"PROPERTY_POSITION"	=> $positionID,
			"PROPERTY_USER"		=> $userID,
			"ACTIVE"			=> "Y"
		);
		$arData = self::getVoteAnswersData($arFilter);
		if ($arData[0]["PROP_VARIANT"] == $variantID) {
			return;
		}
		$PROP = array(
			"POSITION"	=> $positionID,
			"VARIANT"	=> $variantID,
			"USER"		=> $userID
		);
		$ib = new \CIBlockElement;
		if (count($arData) == 0) {
			// Добавляем данные
			$name = '-';
			$arFields = array(
				"MODIFIED_BY"       => $userID,
				"IBLOCK_SECTION_ID" => false,
				"IBLOCK_ID"         => $iblockVoteAnswersID,
				"PROPERTY_VALUES"   => $PROP,
				"NAME"              => $name,
				"PREVIEW_TEXT"		=> $name,
				// "SORT"				=> (is_set($el["SORT"])) ? $el["SORT"] : 500,
				// "DATE_ACTIVE_FROM"	=> ConvertTimeStamp($dateStart, 'FULL'),
				// "DATE_ACTIVE_TO"		=> ConvertTimeStamp($dateEnd, 'FULL')
			);	
			$eid = $ib->Add($arFields);
		}
		else {
			$arFields = array(
				"PROPERTY_VALUES"   => $PROP
			);
			// Изменяем существующие данные
			$ID = $arData[0]["ID"];
			$eid = $ib->Update($ID, $arFields);
		}
	}
	
	public static function deleteVoteAnswer($positionID, $variantID, $userID) {
		GLOBAL $iblockVoteAnswersID;
		$arFilter = array(
			"PROPERTY_POSITION"	=> $positionID,
			"PROPERTY_VARIANT"	=> $variantID,
			"PROPERTY_USER"		=> $userID,
			"ACTIVE"			=> "Y"
		);
		$arData = self::getVoteAnswersData($arFilter);
		if (count($arData) > 0) {
			$ib = new \CIBlockElement;
			// Удаляем существующие данные
			$ID = $arData[0]["ID"];
			$eid = $ib->Delete($ID);
		}
	}
	
	
	//================================================================================
	
	
	public static function getVotesDataByParent($ID) {
		$arFilter = array(
			"PROPERTY_PARENT"	=> $ID,
			"ACTIVE"			=> "Y"
		);
		$arRes = self::getVotesData($arFilter);
		return $arRes;
	}
	
	public static function getPositionsDataByVote($ID) {
		$arFilter = array(
			"PROPERTY_VOTE"		=> $ID,
			"ACTIVE"			=> "Y"
		);
		$arRes = self::getPositionsData($arFilter);
		return $arRes;
	}
	
	public static function getVoteVariantsDataByVote($ID) {
		$arFilter = array(
			"PROPERTY_VOTE"		=> $ID,
			"ACTIVE"			=> "Y"
		);
		$arRes = self::getVoteVariantsData($arFilter);
		return $arRes;
	}
	
	public static function getVoteAnswersFullDataByPosition($ID) {
		$arFilter = array(
			"PROPERTY_POSITION"	=> $ID,
			"ACTIVE"			=> "Y"
		);
		$arRes = self::getVoteAnswersFullData($arFilter);
		return $arRes;		
	}
	
	public static function getVoteAnswersFullDataByPositionUser($positionID, $userID) {
		$arFilter = array(
			"PROPERTY_POSITION"	=> $positionID,
			"PROPERTY_USER"		=> $userID,
			"ACTIVE"			=> "Y"
		);
		$arRes = self::getVoteAnswersFullData($arFilter);
		return $arRes;
	}
	
	public static function getEntityDataByPosition($ID) {
		$arRes = array();
		$arFilter = array(
			"ID" => $ID
		);
		$arData = self::getPositionsData($arFilter, false, true);
		if (!isset($arData)) {
			return NULL;
		}
		$data = $arData[0];
		var_dump($data);
		$type			= $data["PROP_ENTITY_TYPE_XML_ID"];
		$entityID		= $data["PROP_ENTITY"];
		$structureID	= $data["PROP_STRUCTURE"];
		switch ($type) {
		case "TYPE_E":
			// Элемент инфоблока
			$arFilter = array(
				"IBLOCK_ID"	=> $structureID,
				"ID"		=> $entityID
			);
			$arData = CKLIBlockTech::getData($structureID, $arFilter); 
			break;
		case "TYPE_S":
			// Секция инфоблока
			$arFilter = array(
				"IBLOCK_ID"	=> $structureID,
				"ID"		=> $entityID
			);
			$rsData = \CIBlockSection::GetList(array(), $arFilter, false, array("*", "UF_*"));
			$arData = $rsData->GetNext();
			break;
		case "TYPE_H":
			// Таблица highload-блока
			Loader::includeModule("highloadblock");
			$hlbl = $structureID;
			$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
			$entity = HL\HighloadBlockTable::compileEntity($hlblock);
			$entity_data_class = $entity->getDataClass();
			$rsData = $entity_data_class::getList(array(
				"select"	=> array("*"),
				"order"		=> array("ID" => "ASC"),
				"filter"	=> array("ID" => $entityID)
			));
			$arData = $rsData->Fetch();
			break;
		}
		return $arData;
	}
	
	
	//================================================================================
	
	
	// Подсчитывает голоса пользователя в разрезе опросов, возвращает массив [ID опроса] => количество голосов, отданных в данном опросе
	public static function getArVotesCountByUser($voteID, $userID, $isRecursive = true) {
		$arRes = array();
		$arData = self::getPositionsDataByVote($voteID);
		$count = 0;
		foreach ($arData as $data) {
			$positionID = $data["ID"];
			$arDataA = self::getVoteAnswersFullDataByPositionUser($positionID, $userID);
			$count += count($arDataA);
		}
		$arRes[$voteID] = $count;
		if ($isRecursive) {
			$arData = self::getVotesDataByParent($voteID);
			foreach ($arData as $data) {
				$arRes += self::getArVotesCountByUser($data["ID"], $userID, $isRecursive);
			}
		}
		return $arRes;
	}
	
	// Подсчитывает голоса пользователя в разрезе опросов для данного опроса, возращает количество голосов, отданных в данном опросе
	public static function getVotesCountByUser($voteID, $userID) {
		$arData = self::getArVotesCountByUser($voteID, $userID, false);
		return $arData[$voteID];
	}
	
	// Подсчитывает информация по доступным голосам пользователя по данному опросу
	public static function getVotesInfoByUser($voteID, $userID) {
		$arData = self::getVotesDataByID($voteID);
		$maxVotes = $arData["PROP_MAX_VOTES"];
		// $minVotes = $arData["PROP_MAX_VOTES"];
		$ammount = self::getVotesCountByUser($voteID, $userID);
		$votesLeft = $maxVotes - $ammount;
		$stillCanVote = ($votesLeft > 0);
		$arRes = compact('ammount', 'votesLeft', 'stillCanVote');
		return $arRes;
	}
	
	
	//================================================================================
	
	
	public static function countVotes($ID, $isRecursive = true) {
		$arRes = array();
		$arData = self::getPositionsDataByVote($ID);
		foreach ($arData as $data) {
			$positionID = $data["ID"];
			$arDataA = self::getVoteAnswersFullDataByPosition($positionID);
			$value = 0;
			foreach ($arDataA as $dataA) {
				$value += $dataA["VAR_PROP_VALUE"];
			}
			$data["VALUE"] = $value;
			$data["VOTES_COUNT"] = count($arDataA);
			$arRes[] = $data;
		}
		if ($isRecursive) {
			$arData = self::getVotesDataByParent($ID);
			foreach ($arData as $data) {
				$arRes = array_merge($arRes, self::countVotes($data["ID"], $isRecursive));
			}
		}
		return $arRes;
	}
	
	
	//================================================================================
	
	
	public static function getVotesRecursive($ID, $isRecursive = true) {
		
		// 1. Получаем запись об опросе
		// 2. Получением записи о всех связанных позициях для данного опроса
		// 3. Получением оценку пользователя для данной позиции
		// 4. Повторяем пункты 1-3 для всех дочерних опросов
		
		$arRes = array();
		
		// 1. Получаем запись о голосовании
		$arData = self::getVotesDataByID($ID);
		$arData["TYPE"] = 'V';
		$arRes[] = $arData;
		
		// 2. Получением записи о всех связанных позициях для данного голосования
		$arData = self::getPositionsDataByVote($ID);
		foreach ($arData as $data) {
			$positionID = $data["ID"];
			// 3. Получением оценку пользователя для данной позиции
			$arDataA = self::getVoteAnswersFullDataByPosition($positionID);
			$value = 0;
			foreach ($arDataA as $dataA) {
				$value += $dataA["VAR_PROP_VALUE"];
			}
			$data["VALUE"] = $value;
			$data["VOTES_COUNT"] = count($arDataA);
			$data["TYPE"] = 'P';
			$arRes[] = $data;
		}
		
		if ($isRecursive) {
			// 4. Повторяем пункты 1-2 для всех дочерних голосований
			$arData = self::getVotesDataByParent($ID);
			foreach ($arData as $data) {
				$arRes = array_merge($arRes, self::getVotesRecursive($data["ID"], $isRecursive));
			}
		}
		
		return $arRes;
		
	}
	
	public static function getVotesRecursiveByUser($ID, $userID, $isRecursive = true) {
		
		// 1. Получаем запись об опросе
		// 2. Получением записи о всех связанных позициях для данного опроса
		// 3. Получением оценку пользователя для данной позиции
		// 4. Повторяем пункты 1-3 для всех дочерних опросов
		
		$arRes = array();
		
		// 1. Получаем запись о голосовании
		$arData = self::getVotesDataByID($ID);
		$arData["TYPE"] = 'V';
		$arRes[] = $arData;
		
		// 2. Получением записи о всех связанных позициях для данного голосования
		$arData = self::getPositionsDataByVote($ID);
		foreach ($arData as $data) {
			$positionID = $data["ID"];
			// 3. Получением оценку пользователя для данной позиции
			$arDataA = self::getVoteAnswersFullDataByPositionUser($positionID, $userID);
			$value = 0;
			foreach ($arDataA as $dataA) {
				$value += $dataA["VAR_PROP_VALUE"];
			}
			$data["VALUE"] = $value;
			$data["VOTES_COUNT"] = count($arDataA);
			$data["TYPE"] = 'P';
			$arRes[] = $data;
		}
		
		if ($isRecursive) {
			// 4. Повторяем пункты 1-2 для всех дочерних голосований
			$arData = self::getVotesDataByParent($ID);
			foreach ($arData as $data) {
				$arRes = array_merge($arRes, self::getVotesRecursiveByUser($data["ID"], $userID, $isRecursive));
			}
		}
		
		return $arRes;
		
	}
	
}
