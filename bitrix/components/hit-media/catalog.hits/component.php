<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
		die();
	}

	CModule::IncludeModule('iblock');
	CModule::IncludeModule('sale');

	global $DB;
	$q = "SELECT
			tovar.ID
			FROM
			b_iblock_element as tovar
		WHERE
		   tovar.IBLOCK_ID IN(3,4,10) AND
		   !isNull(tovar.DETAIL_PICTURE)

		   ORDER BY RAND()
		   LIMIT 3
			";
	$t = $DB->Query($q);
	while ($temp = $t->Fetch()) {
		$ids[] = $temp['ID'];
	}
	$res = CIBlockElement::GetList(array(), array(
			'ID' => $ids,
		));
	while ($tovar = $res->GetNext()) {
		$prices              = CPrice::GetList(array(), array("PRODUCT_ID" => $tovar['ID']))
				->Fetch();
		$tovar['PRICES']     = $prices;
		$arResult['ITEMS'][] = $tovar;
	}

	$this->IncludeComponentTemplate();
?>