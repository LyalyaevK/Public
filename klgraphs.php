<?

//==================== Класс для работы с графами ====================

namespace Bitrix\Main\Lyalyaev;

use \Bitrix\Main\Loader;
use \Bitrix\Highloadblock as HL; 
use \Bitrix\Main\Entity;

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Lyalyaev\CLyalyaev;
use \Bitrix\Main\Lyalyaev\CKLIBlock;
use \Bitrix\Main\Lyalyaev\CKLConstants;
use \Bitrix\Main\Lyalyaev\CKLArrays;
use \Bitrix\Main\Lyalyaev\CKLHLRandomData as RD;

// Глобальные переменные

const AREDGETYPE = array(
	'simple', 'directed'
);

const ARGRAPHTYPE = array(
	'simple', 'directed', 'mixed'
);

const ARTREENODETYPE = array(
	'array', 'key', 'value'
);



$userAdminID 		= RD::getData('USER_ADMIN_ID')[0]["VALUE"];
$userGDID 			= RD::getData('USER_GD_ID')[0]["VALUE"];
$userNODATAID 		= RD::getData('USER_NODATA_ID')[0]["VALUE"];



// Рёбра графа

class CKLGraphEdgeBasic {
	
	protected $type;
	protected $arLink;
	
	public function __construct($type = NULL, ?CKLGraphNode $nodeFrom = NULL, ?CKLGraphNode $nodeTo = NULL) {
		$this->setType($type);
		$this->setArLink($nodeFrom, $nodeTo);
	}
	
	public function __destruct() {
    }
	
	public function setType($type = NULL) {
		$this->type	= AREDGETYPE[$type];
	}
	
	public function setArLink(?CKLGraphNode $nodeFrom = NULL, ?CKLGraphNode $nodeTo = NULL) {
		$arLink = array();
		if ($this->type == 'directed') {
			$arLink['from']	= $nodeFrom;
			$arLink['to']	= $nodeTo;
		}
		else {
			$arLink[]		= $nodeFrom;
			$arLink[]		= $nodeTo;
		}
		$this->arLink = $arLink;
	}
	
	public function getType()		{return $this->type;}
	public function getArLink()		{return $this->arLink;}
	public function getNodeFrom() {
		if ($this->type == 'directed') {
			return $this->arLink['from'];
		}
		else {
			return $this->arLink[0];
		}
	}
	public function getNodeTo() {
		if ($this->type == 'directed') {
			return $this->arLink['to'];
		}
		else {
			return $this->arLink[1];
		}
	}
	public function getEdgeData() {
		$arRes = array(
			"type"		=> $this->type,
			"arLink"	=> $this->arLink
		);
		return $arRes;
	}
	
	public function switchLink() {
		$arLink = $this->arLink;
		$key1 = array_key_first($arLink);
		$key2 = array_key_last($arLink);
		$this->setArLink($arLink[$key2], $arLink[$key1]);
	}
	
}

class CKLGraphEdge extends CKLGraphEdgeBasic {
	
	protected $value;
	protected $data;
	
	public function __construct($type = NULL, ?CKLGraphNode $nodeFrom = NULL, ?CKLGraphNode $nodeTo = NULL, $value = NULL, $data = NULL) {
		parent::__construct($type, $nodeFrom, $nodeTo);
		$this->setValue($value);
		$this->setData($data);
	}
	
	public function __destruct() {
    }
	
	public function setValue($value = NULL) {
		$this->value = $value;
	}
	
	public function setData($data = NULL) {
		$this->data = $data;
	}
	
	public function getValue()		{return $this->value;}
	public function getData()		{return $this->data;}
	
	public function getEdgeData() {
		$arRes = array(
			"type"		=> $this->type,
			"arLink"	=> $this->arLink,
			"value"		=> $this->value,
			"data"		=> $this->data
		);
		return $arRes;
	}
	
}



// Узлы графа

class CKLGraphNodeBasic {
	
	protected $value;
	
	public function __construct($value = NULL) {
		$this->setValue($value);
	}
	
	public function __destruct() {
    }
	
	public function getValue()	{return $this->value;}
	
	public function getNodeData() {
		$arRes = array(
			"value"	=> $this->value
		);
		return $arRes;
	}
	
	public function setValue($value = NULL) {
		$this->value = $value;
	}
	
}

class CKLGraphNode extends CKLGraphNodeBasic {
	
	protected $type;
	protected $data;
	
	public function __construct($value = NULL, $type = NULL, $data = NULL) {
		parent::__construct($value);
		$this->setType($type);
		$this->setData($data);
	}
	
	public function __destruct() {
    }
	
	public function getType()	{return $this->type;}
	public function getData()	{return $this->data;}
	
	public function getNodeData() {
		$arRes = array(
			"value"	=> $this->value,
			"type"	=> $this->type,
			"data"	=> $this->data
		);
		return $arRes;
	}
	
	public function setType($type = NULL) {
		$this->type	= $type;
	}
	
	public function setData($data = NULL) {
		$this->data	= $data;
	}
	
}

class CKLTreeNode extends CKLGraphNode {
	
	public function setType($type = NULL) {
		$this->type	= ARTREENODETYPE[$type];
	}
	
}

class CKLGraphNodeWithName extends CKLGraphNode {
	
	protected $name;
	
	public function __construct($value = NULL, $type = NULL, $data = NULL, $name = NULL) {
		parent::__construct($value);
		$this->setName($name);
	}
	
	public function __destruct() {
    }
	
	public function getName()	{return $this->name;}
	
	public function getNodeData() {
		$arRes = array(
			"value"	=> $this->value,
			"type"	=> $this->type,
			"data"	=> $this->data,
			"name"	=> $this->name
		);
		return $arRes;
	}
	
	public function setName($name = NULL) {
		$this->name	= $name;
	}
	
}

class CKLUsersTreeNode extends CKLGraphNodeWithName {
}

class CKLDepsTreeNode extends CKLGraphNodeWithName {
}


// Граф

class CKLGraph {
	
	protected $type;
	protected $arNodes;
	protected $arEdges;
	
	public function __construct($type = NULL, $arNodes = NULL, $arEdges = NULL) {
		$this->setType($type);
		$this->setArNodes($arNodes);
		$this->setArEdges($arEdges);
	}
	
	public function __destruct() {
    }
	
	public function setType($type = NULL) {
		$this->type	= ARGRAPHTYPE[$type];
	}
	
	public function setArNodes($arNodes = NULL) {
		if (!isset($arNodes))
			$this->arNodes = array();
		else
			$this->arNodes = CKLArrays::makeArray($arNodes);
	}

	public function setArEdges($arEdges = NULL) {
		if (!isset($arEdges))
			$this->arEdges = array();
		else
			$this->arEdges = CKLArrays::makeArray($arEdges);
	}
	
	public function getType()		{return $this->type;}
	public function getArNodes()	{return $this->arNodes;}
	public function getArEdges()	{return $this->arEdges;}
	
	public function getGraphData() {
		$arRes = array(
			"arNodes"	=> $this->arNodes,
			"arEdges"	=> $this->arEdges,
		);
		return $arRes;
	}
	
	public function isConnected() {
		$arNodes		= $this->arNodes;
		$arEdgeNodes	= CKLGraphFunctions::getNodesForArEdges($this->$arEdges);
		$arDiff 		= CKLGraphFunctions::arrayDiff($arNodes, $arEdgeNodes);
		$res = (count($arDiff) > 0);
		return $res;
	}
	
}

class CKLTree extends CKLGraph {
	
	protected $assocKeysOnly;
	
	public function __construct(?bool $assocKeysOnly = true, $arNodes = NULL, $arEdges = NULL) {
		$this->setAssocKeysOnly($assocKeysOnly);
		parent::__construct(1, $arNodes, $arEdges);
	}
	
	public function getAssocKeysOnly() {return $this->assocKeysOnly;}
	
	public function setAssocKeysOnly(?bool $assocKeysOnly) {
		$this->assocKeysOnly = $assocKeysOnly;
	}
	
	public function createTreeFromArray($arData, $assocKeysOnly = true) {
		$arNodes = array();
		$arEdges = array();
		CKLGraphFunctions::createTreeDataFromArray($arData, $assocKeysOnly, $arNodes, $arEdges);
		// Удалям висячие рёбра и переиндексируем массив рёбер
		CKLGraphFunctions::deleteNullFromEdges($arEdges);
		$arEdges = array_values($arEdges);
		$this->__construct($assocKeysOnly, $arNodes, $arEdges); 
	}
	
}

class CKLUsersTree extends CKLGraph {
}

class CKLDepsTree extends CKLGraph {
}

// Функции работы с графами

class CKLGraphFunctions {
	
	// Удаляет связи определённого типа между двумя узлами
	public static function deleteLinks($node1, $node2, $arEdges, $type) {
		$type = AREDGETYPE[$type];
		foreach ($arEdges as $key => $edge) {
			$arEdgeData = $edge->getEdgeData();
			extract($arEdgeData);
			$key1 = arrays_key_first($arLink);
			$key2 = arrays_key_last($arLink);
			if ($type == 'directed')
				if ($node1 === $arLink[$key1] && $node2 === $arLink[$key2])
					unset($arEdges[$key]);
			else
				if ($node1 === $arLink[$key1] && $node2 === $arLink[$key2] || $node1 === $arLink[$key2] && $node2 === $arLink[$key1])
					unset($arEdges[$key]);
		}
	}
	
	// Получает все рёбра для данного узла
	public static function getEdgesForNode($node, $arEdges) {
		$arRes = array();
		foreach ($arEdges as $edge) {
			$arLink = $edge->getArLink();
			if (in_array($node, $arLink))
				$arRes[] = $edge;
		}
		return $arRes;
	}
	
	// Фильтрует  массив рёбер по типу
	public static function getNodesByType($arEdges, $type) {
		$type = AREDGETYPE[$type];
		$arRes = array();
		foreach ($arEdges as $edge) {
			if ($edge->getType() == $type) {
				$arRes[] = $edge;
			}
		}
		return $arRes;
	}
	
	// Получает массив узлов для массива рёбер по типу (начальные узлы, конечные узлы, все)
	public static function getNodesForArEdges($arEdges, $type = 'all') {
		$arRes = array();
		foreach ($arEdges as $edge) {
			$arLink = $edge->getArLink();
			if ($type == 'from') {
				$k = array_key_first($arLink);
				$arRes[] = $arLink[$k];
			}
			elseif ($type == 'to') {
				$k = array_key_last($arLink);
				$arRes[] = $arLink[$k];
			}
			else {
				foreach ($arLink as $node) {
					$arRes[] = $node;
				}
			}
		}
		$arRes = array_unique($arRes, SORT_REGULAR);
		return $arRes;
	}
	
	// Получает рёбра, где данный узел является узлом определённого типа
	public static function getNodeTypeEdges($node, $arEdges, $type = 'all') {
		$arRes = array();
		foreach ($arEdges as $edge) {
			$arLink = $edge->getArLink();
			if ($type == 'all') {
				if (in_array($node, $arLink))
					$arRes[] = $edge;
			}
			else {
				if ($arLink[$type] === $node)
					$arRes[] = $edge;
			}
		}
		return $arRes;
	}
	
	// Получает рёбра, где данный узел является начальным
	public static function getNodeFromEdges($node, $arEdges) {
		return self::getNodeTypeEdges($node, $arEdges, 'from');
	}
	
	// Получает рёбра, где данный узел является конечным
	public static function getNodeToEdges($node, $arEdges) {
		return self::getNodeTypeEdges($node, $arEdges, 'to');
	}
	
	
	
	// Удаляет рёбра соответствующего типа, где узел равен значению (может быть NULL - пустой узел)
	public static function deleteEdgeByTypeValue(&$arEdges, $type, $value = NULL) {
		foreach ($arEdges as $key => $edge) {
			$arLink = $edge->getArLink();
			if ($type == 'from')
				$k = array_key_first($arLink);
			elseif ($type == 'to')
				$k = array_key_last($arLink);
			$arLink = $edge->getArLink();
			if ($arLink[$k] === $value)
				unset($arEdges[$key]);
		}
	}
	
	// Удаляет рёбра, где начальный узел равен значению (может быть NULL - пустой узел)
	public static function deleteNullEdgesFrom(&$arEdges) {
		self::deleteEdgeByTypeValue($arEdges, 'from');
	}
	
	// Удаляет рёбра, где конечный узел равен значению (может быть NULL - пустой узел)
	public static function deleteNullEdgesTo(&$arEdges) {
		self::deleteEdgeByTypeValue($arEdges, 'to');
	}
	
	// Удаляет рёбра, где какой-либо из узлов равен значению (может быть NULL - пустой узел)
	public static function deleteNullFromEdges(&$arEdges) {
		self::deleteNullEdgesFrom($arEdges);
		self::deleteNullEdgesTo($arEdges);
	}
	
	
	
	// Создаёт данные для дерева из массива
	public static function createTreeDataFromArray($arData, $assocKeysOnly, &$arNodes = array(), &$arEdges = array(), $parent = NULL) {
		
		if (!is_array($arData)) {
			// Создаём узел для элемента массива
			$node = new CKLTreeNode(2, $arData);
			$arNodes[] = $node;
			// Создаём ребро
			$edge = new CKLGraphEdge(1, $parent, $node);
			$arEdges[] = $edge;
			return;
		}
		
		// Создаём узел для массива
		$node = new CKLTreeNode(0, NULL);
		$arNodes[] = $node;
		// Создаём ребро
		$edge = new CKLGraphEdge(1, $parent, $node);
		$arEdges[] = $edge;
		
		$parentNode = $node;
		
		$isAssoc = CKLArrays::isAssoc($arData);
		foreach ($arData as $key => $data) {
			// Создаём узел для ключа массива
			// Обработка заполнения значения для ключа
			if ($assocKeysOnly) {
				$value = ($isAssoc) ? $key : NULL;
			}
			else {
				$value = $key;
			}
			$node = new CKLTreeNode(1, $value);
			$arNodes[] = $node;
			// Создаём ребро между массивом и текущим ключом
			$edge = new CKLGraphEdge(1, $parentNode, $node);
			$arEdges[] = $edge;
			// Создаём узлы и рёбра для всех дочерних элементов текущего массива
			$arChildNodes = self::createTreeDataFromArray($data, $assocKeysOnly, $arNodes, $arEdges, $node);
			// Создаём рёбра между текущим узлом (ключом массива) и этими элементами (значениями массива)
			foreach ($arChildNodes as $child) {
				$edge = new CKLGraphEdge(1, $node, $child);
				$arEdges[] = $edge;
			}
		}
		
		// $graph = new(1, $arNodes, $arEdges);
		
		// return $graph;
		
	}
	
	// Создаёт дерево из массива
	public static function createTreeFromArray($arData, $assocKeysOnly = true, $arNodes = array(), $arEdges = array(), $parent = NULL) {
		
		$arNodes = array();
		$arEdges = array();
		
		self::createTreeDataFromArray($arData, $assocKeysOnly, $arNodes, $arEdges);
		
		// Удалям висячие рёбра и переиндексируем массив рёбер
		self::deleteNullFromEdges($arEdges);
		$arEdges = array_values($arEdges);
		
		$tree = new CKLTree($assocKeysOnly, $arNodes, $arEdges);
		
		return $tree;
		
	}
	
	// Получает разность массивов
	public static function arrayDiff($arData1, $arData2) {
		$arRes = array();
		foreach ($arData1 as $data) {
			if (!in_array($data, $arData2))
				$arRes[] = $data;
		}
		return $arRes;
	}
	
	// Получает пересечение массивов
	public static function arrayIntersect($arData1, $arData2) {
		$arRes = array();
		foreach ($arData1 as $data) {
			if (in_array($data, $arData2))
				$arRes[] = $data;
		}
		return $arRes;
	}
	
	// Получает объелинение массивов
	public static function arrayCombine($arData1, $arData2) {
		$arRes = array_merge($arData1, $arData2);
		$arRes = array_unique($arRes, SORT_REGULAR);
		return $arRes;
	}
	
	// Проверка на связанность графа с узлами $arNodes и рёбрами $arEdges
	public static function isConnected($arNodes, $arEdges) {
		$arEdgeNodes = self::getNodesForArEdges($arEdges);
		foreach ($arEdgeNodes as $node) {
			$arReachableNodes = self::getReachableNodes($arEdges, $node);
			$arReachableNodes[] = $node;
			$arReachableNodes = array_unique($arReachableNodes, SORT_REGULAR);
			$arDiff = self::arrayDiff($arNodes, $arReachableNodes);
			if (count($arDiff) > 0)
				return false;
		}
		return true;
	}
	
	public static function compare($obj1, $obj2) {
		$res = (int) ($obj1 !== $obj2);
		return $res;
	}
	
	public static function compareEdgeValuesAsc($edge1, $edge2) {
		$value1 = $edge1->getValue();
		$value2 = $edge2->getValue();
		if ($value1 == $value2) {
			return 0;
		}
		return ($value1 < $value2) ? -1 : 1;
	}
	
	public static function compareEdgeValuesDesc($edge1, $edge2) {
		$value1 = $edge1->getValue();
		$value2 = $edge2->getValue();
		if ($value1 == $value2) {
			return 0;
		}
		return ($value1 < $value2) ? 1 : -1;
	}
	
	// Сортирует массив рёбер по значениям
	public static function sortArEdgesByValue(&$arEdges, $type = 'ASC') {
		$type = trim(strtoupper($type));
		if ($type == 'ASC') {
			uasort($arEdges, array('self', 'compareEdgeValuesAsc'));
		}
		elseif ($type == 'DESC') {
			uasort($arEdges, array('self', 'compareEdgeValuesDesc'));
		}
	}
	
	// Получает рёбра к узлам относительно $arNodes ("новым" или "старым")
	public static function getEdgesToTypeNodes($arEdges, $arNodes, $type = 'new') {
		$type = trim(strtolower($type));
		$arRes = array();
		foreach ($arEdges as $key => $edge) {
			$toNew = false;
			$arLink = $edge->getArLink();
			foreach ($arLink as $node) {
				if (!in_array($node, $arNodes)) {
					$toNew = true;
					break;
				}
			}
			// var_dump($toNew);
			if ($type == 'new' && $toNew || $type == 'old' && !$toNew) {
				$arRes[$key] = $edge;
			}
		}
		return $arRes;
	}
	
	// Получает рёбра к "новым" (вне узлов $arNodes) узлам
	public static function getEdgesToNewNodes($arEdges, $arNodes) {
		return self::getEdgesToTypeNodes($arEdges, $arNodes, 'new');
	}
	
	// Получает рёбра к "старым" (в узлах $arNodes) узлам
	public static function getEdgesToOldNodes($arEdges, $arNodes) {
		return self::getEdgesToTypeNodes($arEdges, $arNodes, 'old');
	}
	
	// Получает достижимые узлы для данного
	public static function getReachableNodes(&$arEdges, $node, $type = 'all', &$arRes = array(), $i = 0) {
		
		$i++;
		
		if ($i == 1) {
			$arReturnEdges = $arEdges;
		}
		
		foreach ($arEdges as $key => $edge) {
			
			$isCounted = false;
			$arLink = $edge->getArLink();
			if ($type == 'all') {
				if (in_array($node, $arLink)) {
					$isCounted = true;
					$arNodes = $arLink;
					foreach ($arNodes as $k => $n) {
						if ($node === $n) {
							unset($arNodes[$k]);
						}
					}
				}
			}
			else {
				if ($arLink[$Type] === $node) {
					$isCounted = true;
					$edge->switchLink();
					$arNodes = array($arLink[$type]);
				}
			}
			
			$arDiff = self::arrayDiff($arNodes, $arRes);
			if (count($arDiff) == 0) {
				$arNodes = array();
			}
			
			if ($isCounted) {
				unset($arEdges[$key]);
			}
			// echo '<pre>'; print_r($arNodes); echo '</pre>';
			// $arNodes = self::arrayDiff($arNodes, [$node]);
			
			foreach ($arNodes as $n) {
				$arRes[] = $node;
				if ($n !== $node) {
					self::getReachableNodes($arEdges, $n, $type, $arRes, $i);
				}
			}
		}
		
		if ($i == 1) {
			$arEdges = $arReturnEdges;
		}
		
		return $arRes;
	}
	
	
	
	// Алгоритм Прима для рёбер (общая версия)
	public static function algPrimGeneral($arEdges, &$arRes = array(), $arNodes = NULL, $amNodes = NULL, $i = 0) {
		
		$i++;
		
		if ($arNodes === NULL) {
			$arNodes = self::getNodesForArEdges($arEdges);
			if (count($arNodes) == 0)
				return;
			$arNodes = array_unique($arNodes, SORT_REGULAR);
			$amNodes = count($arNodes);
			$arNodes = array($arNodes[0]);
		}
		
		$arCurEdges = array();
		foreach ($arNodes as $node) {
			$arCurEdges = array_merge($arCurEdges, self::getNodeTypeEdges($node, $arEdges));
		}
		$arCurEdges = array_unique($arCurEdges, SORT_REGULAR);
		$arCurEdges = self::arrayDiff($arCurEdges, $arRes);
		
		/*
		$arValues = array();
		foreach ($arCurEdges as $key => $edge) {
			$arLink = $edge->getArLink();
			foreach ($arLink as $node) {
				if (!in_array($node, $arNodes)) {
					$arValues[$key] = $edge->getValue();
					break;
				}
			}
		}
		asort($arValues);
		$key = array_key_first($arValues);
		$curEdge = $arCurEdges[$key];
		*/
		
		$arCurEdges = self::getEdgesToNewNodes($arCurEdges, $arNodes);
		self::sortArEdgesByValue($arCurEdges, 'ASC');
		$key = array_key_first($arCurEdges);
		$curEdge = $arCurEdges[$key];
		
		$arRes[] = $curEdge;
		$arLink = $curEdge->getArLink();
		foreach ($arLink as $node) {
			$arNodes[] = $node;
		}
		$arNodes = array_unique($arNodes, SORT_REGULAR);
		if (count($arNodes) != $amNodes && $i <= 10000) {
			self::algPrimGeneral($arEdges, $arRes, $arNodes, $amNodes, $i);
		}
		else {
			return $arRes;
		}
		
		return $arRes;
		
	}
	
	// Алгоритм обратного удаления для рёбер (общая версия)
	public static function algReverseDeleteGeneral($arEdges) {
		
		$arRes = $arEdges;
		
		$arNodes = self::getNodesForArEdges($arEdges);
		$arNodes = array_unique($arNodes, SORT_REGULAR);
		if (count($arNodes) == 0)
			return $arRes;
		
		$isConnected = self::isConnected($arNodes, $arRes);
		if (!isConnected)
			return $arRes;
		
		self::sortArEdgesByValue($arRes, 'DESC');
		
		foreach ($arRes as $key => $edge) {
			// $arTemp = $arRes;
			// unset($arTemp[$key]);
			unset($arRes[$key]);
			// $isConnected = self::isConnected($arNodes, $arTemp);
			$isConnected = self::isConnected($arNodes, $arRes);
			if ($isConnected) {
				// unset($arRes[$key]);
			}
			else {
				$arRes[$key] = $edge;
			}
		}
		
		ksort($arRes);
		return $arRes;
		
	}
	
	// Алгоритм Дейкстры (общая версия)
	public static function algDijkstraGeneral($arEdges) {
	}
	
	
	
	public static function getActiveUsersForLDAP($ldap = '1', $addServiceUsers = true) {
		GLOBAL $userAdminID, $userNODATAID;
		$arRes = array();
		$arFilter = array(
			"ACTIVE" => "Y",
			"EXTERNAL_AUTH_ID" => "LDAP#{$ldap}"
		);
		$rsUsers = \CUser::GetList(($by = array("ID" => "asc")), ($order = "asc"), $arFilter, array('SELECT' => array("UF_*")));
		while ($arUser = $rsUsers->fetch()) {
			$arRes[] = $arUser["ID"];
		}
		if ($addServiceUsers) {
			// $arRes[] = $userAdminID;
			$arRes[] = $userNODATAID;
		}
		$arRes = array_unique($arRes);
		return $arRes;
	}
	
	public static function makeUsersTreeByManager() {
		// GLOBAL $userGDID;
		$arNodes = array();
		$arEdges = array();
		$arUsers = self::getActiveUsersForLDAP();
		foreach ($arUsers as $userID) {
			$arNodes[$userID] = new CKLUsersTreeNode(NULL, $userID);
		}
		foreach ($arUsers as $userID) {
			$arUser = \CUser::GetByID($userID)->fetch();
			$managerID = ($userID != $arUser["UF_MANAGER_ZGD"]) ? $arUser["UF_MANAGER_ZGD"] : NULL;
			$arEdges[] = new CKLGraphEdge(1, $arNodes[$managerID], $arNodes[$userID]);
		}
		$tree = new CKLUsersTree(1, $arNodes, $arEdges);
		return $tree;
	}
	
	public static function makeDepsTree() {
		// GLOBAL $userGDID;
		$arNodes = array();
		$arEdges = array();
		$arFilter = array("CODE" => "departments");
		$iblock_id = CKLIBlock::getIBlockIDByCode($arFilter);		
		$arFilter = Array(
			'IBLOCK_ID'	=> $iblock_id,
			'ACTIVE'	=> "Y"
        );
		$arDeps = array();
		$rsDeps = \CIBlockSection::GetList(array(), $arFilter, false, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "DESCRIPTION", "DEPTH_LEVEL", "UF_*"));
		while ($arDep = $rsDeps->GetNext()) {
			$depID = $arDep["ID"];
			$arNodes[$depID] = new CKLDepsTreeNode(NULL, $depID);
			$arDeps[] = $arDep;
		}
		foreach ($arDeps as $arDep) {
			$depID = $arDep["ID"];
			$headDepID = $arDep["IBLOCK_SECTION_ID"];
			$arEdges[] = new CKLGraphEdge(1, $arNodes[$headDepID], $arNodes[$depID]);
		}
		$tree = new CKLDepsTree(1, $arNodes, $arEdges);
		return $tree;
	}
	
	public static function makeUsersTreeFromHLTestsUsersManagers($periodID) {
		// GLOBAL $userGDID;
		Loader::includeModule("highloadblock");
		$hlblName = 'TestsUsersManagers';
		$hlbl = CKLIBlock::GetHighLoadBlockIDByName($hlblName);
		$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
		$entity = HL\HighloadBlockTable::compileEntity($hlblock);
		$entity_data_class = $entity->getDataClass();
		$rsData = $entity_data_class::getList(array(
			"select" => array("*"),
			"order" => array("ID" => "ASC"),
			"filter" => array("UF_PERIOD" => $periodID)
		));
		$arData = array();
		$arUsers = array();
		while ($arROW = $rsData->Fetch()) {
			$arData[] = $arROW;
			$arUsers[$arROW["UF_USER"]] = $arROW["UF_USER"];
			$arUsers[$arROW["UF_MANAGER"]] = $arROW["UF_MANAGER"];
		}
		$arNodes = array();
		$arEdges = array();
		foreach ($arUsers as $userID) {
			$arNodes[$userID] = new CKLUsersTreeNode(NULL, $userID);
		}
		foreach ($arData as $data) {
			$userID = $data["UF_USER"];
			$managerID = ($userID != $data["UF_MANAGER"]) ? $data["UF_MANAGER"] : NULL;
			$arEdges[] = new CKLGraphEdge(1, $arNodes[$managerID], $arNodes[$userID]);
		}
		$tree = new CKLUsersTree(1, $arNodes, $arEdges);
		return $tree;
	}
	
	public static function getPathDirectedBasic($nodeStart, $nodeEnd, $arEdges, &$arRes = NULL, $arCur = NULL) {
		if ($arRes === NULL) {
			$arRes = array();
			$arCur = array();
		}
		if ($nodeEnd === NULL) {
			return;
		}
		if (array_search($nodeEnd, $arCur) !== false) {
			return;
		}
		$arCur[] = $nodeEnd;
		if ($nodeEnd === $nodeStart) {
			$arRes[] = $arCur;
		}
		$arParents = array();
		$arE = self::getNodeToEdges($nodeEnd, $arEdges);
		foreach ($arE as $e) {
			$arParents[] = $e->getNodeFrom();
		}
		foreach ($arParents as $p) { 
			self::getPathDirectedBasic($nodeStart, $p, $arEdges, $arRes, $arCur);
		}
		return $arRes;
	}
	
	public static function getPathDirected($nodeStart, $nodeEnd, $arEdges, $fromTop = true) {
		$arRes = self::getPathDirectedBasic($nodeStart, $nodeEnd, $arEdges);
		if ($fromTop) {
			foreach ($arRes as $key => $row) {
				$row = array_reverse($row);
				$arRes[$key] = $row;
			}
		}
		return $arRes;
	}
	
	public static function getSubTree($node, $arEdges, $direction, &$arNewNodes = NULL, &$arNewEdges = NULL, $level = 0) {
		// to - вверх
		// from - вниз
		$direction = trim(strtolower($direction));
		if ($level == 0) {
			if ($direction != 'from' && $direction != 'to')
				return NULL;
			if ($arNewNodes === NULL)
				$arNewNodes = array($node);
			if ($arNewEdges === NULL)
				$arNewEdges = array();
		}
		$arE = ($direction == 'to') ? self::getNodeToEdges($node, $arEdges) : self::getNodeFromEdges($node, $arEdges);
		$arNewEdges = array_merge($arNewEdges, $arE);
		$arLocalNodes = array();
		foreach ($arE as $e) {
			$arLocalNodes[] = ($direction == 'to') ? $e->getNodeFrom() : $e->getNodeTo();
		}
		foreach ($arLocalNodes as $key => $localNode) {
			if ($localNode === NULL) {
				unset($arLocalNodes[$key]);
				continue;
			}
			self::getSubTree($localNode, $arEdges, $direction, $arNewNodes, $arNewEdges, $level + 1);
		}
		$arNewNodes = array_merge($arNewNodes, $arLocalNodes);
		if ($level > 0) {
			return;
		}
		else {
			$newTree = new CKLUsersTree(1, $arNewNodes, $arNewEdges);
			return $newTree;
		}
			
	}
	
}