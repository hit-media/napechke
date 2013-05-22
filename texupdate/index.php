<?
$_SERVER["DOCUMENT_ROOT"] = '/home/n/napechke/public_html';

define("PUBLIC_AJAX_MODE", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("NO_AGENT_CHECK", true);
define("DisableEventsCheck", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>
<?
$APPLICATION->IncludeComponent("bitrix:site.tex.update", "", array(),false);

//$id = $_GET['up'];
//
//$FILE_DIR = $_SERVER["DOCUMENT_ROOT"] . "/" . COption::GetOptionString("main", "upload_dir", "upload") . "/download/";
//
//$list = CIBlockElement::GetList(
//	Array(
//		"ID" => "ASC"
//	),
//	Array(
//		"DETAIL_PICTURE" => null,
//		"IBLOCK_ID" => "4",
//		"ACTIVE" => "Y",
//		">ID" => $id
//	)
//);
//
//while ($element = $list->Fetch()) {
//	if (!$element['DETAIL_PICTURE'] && $element['XML_ID']) {
//		$img_filename = $FILE_DIR . $element['XML_ID'] . ".jpg";
//		$img_url = "http://forum3.ru/descriptions/descr1/" . $element['XML_ID'] . ".JPG";
//		$img_hash = file_get_contents($img_url);
//
//		if ($img_hash != false) {
//			if (strpos($img_hash, '<html>') === false) {
//				$img_file = fopen($img_filename, "w");
//				fwrite($img_file, $img_hash);
//				fclose($img_file);
//				$arFields = array(
//					'DETAIL_PICTURE' => CFile::MakeFileArray($img_filename)
//				);
//				$el = new CIBlockElement;
//				$IDE = $el->Update($element['ID'], $arFields);
//			}
//		}
//
//		echo '<pre>';
//		var_dump($img_hash);
//		print_r($element);
//		echo '</pre>';
//		die();
//	}
//}

?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); ?>