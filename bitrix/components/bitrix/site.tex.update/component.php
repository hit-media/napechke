123
<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
		die();
	}

	ob_start();

	if (!CModule::IncludeModule("iblock")) {
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}


	function nacenka($price)
	{
		$nacenka = array(//список наценок в процентах в зависимости от суммы
			'10' => '100',
			'50' => '50',
			'100' => '30',
			'250' => '25',
			'500' => '20',
			'1000' => '15',
			'5000' => '12',
			'10000' => '10',
			'20000' => '8'
		);

		$procent = 7;
		foreach ($nacenka as $key => $vol) {
			if($price < $key){
				$procent = $vol;
			}
		}
		$edinica = $price / 100;
		return (int)$price + ((int)$edinica * (int)$procent);
	}

	function translit($str)
	{
		$tr  = array(
			"А" => "a",
			"Б" => "b",
			"В" => "v",
			"Г" => "g",
			"Д" => "d",
			"Е" => "e",
			"Ж" => "j",
			"З" => "z",
			"И" => "i",
			"Й" => "y",
			"К" => "k",
			"Л" => "l",
			"М" => "m",
			"Н" => "n",
			"О" => "o",
			"П" => "p",
			"Р" => "r",
			"С" => "s",
			"Т" => "t",
			"У" => "u",
			"Ф" => "f",
			"Х" => "h",
			"Ц" => "ts",
			"Ч" => "ch",
			"Ш" => "sh",
			"Щ" => "sch",
			"Ъ" => "",
			"Ы" => "yi",
			"Ь" => "",
			"Э" => "e",
			"Ю" => "yu",
			"Я" => "ya",
			"а" => "a",
			"б" => "b",
			"в" => "v",
			"г" => "g",
			"д" => "d",
			"е" => "e",
			"ж" => "j",
			"з" => "z",
			"и" => "i",
			"й" => "y",
			"к" => "k",
			"л" => "l",
			"м" => "m",
			"н" => "n",
			"о" => "o",
			"п" => "p",
			"р" => "r",
			"с" => "s",
			"т" => "t",
			"у" => "u",
			"ф" => "f",
			"х" => "h",
			"ц" => "ts",
			"ч" => "ch",
			"ш" => "sh",
			"щ" => "sch",
			"ъ" => "y",
			"ы" => "yi",
			"ь" => "",
			"э" => "e",
			"ю" => "yu",
			"я" => "ya",
			" " => "_",
			"." => "",
			"/" => "_",
			"-" => ""
		);
		$str = trim($str);
		$str = strtr($str, $tr);
		$str = strtolower($str);
		$str = preg_replace('/[^a-z0-9_]/', '', $str);
		return $str;
	}

	$runtime = microtime(true);

	echo '<br>memory usage: ' . memory_get_usage(true) . "<br>";

	$FILE_NAME = $_SERVER["DOCUMENT_ROOT"] . "/texupdate/files/forum_price_xml_rub.zip";
	$FILE_WORK_DIR_NAME = $_SERVER["DOCUMENT_ROOT"] . "/texupdate/files/unzip/forum_price_xml_rub.xml";
	$WORK_DIR_NAME = $_SERVER["DOCUMENT_ROOT"] . "/texupdate/files/unzip/";

	$fsourse = "http://forum3.ru/download/forum_price_xml_rub.zip";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $fsourse);
	curl_setopt($ch, CURLOPT_TIMEOUT, 300);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$st = curl_exec($ch);
	$fd = @fopen($FILE_NAME, "w");
	fwrite($fd, $st);
	@fclose($fd);
	curl_close($ch);

	$zip = new ZipArchive;
	$res = $zip->open($FILE_NAME);
	if ($res === true) {
		echo 'ok';
		$zip->extractTo($WORK_DIR_NAME);
		$zip->close();
	}
	else {
		echo 'failed, code:' . $res;
	}

	$zip = zip_open($FILE_NAME);
	if ($zip) {
		while ($zip_entry = zip_read($zip)) {
			$fp = fopen($WORK_DIR_NAME . zip_entry_name($zip_entry), "w");
			if (zip_entry_open($zip, $zip_entry, "r")) {
				$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
				fwrite($fp, "$buf");
				zip_entry_close($zip_entry);
				fclose($fp);
			}
		}
		zip_close($zip);
	}
	printf("<br><center>%.5f с</center>", microtime(true) - $runtime . "<br>");

	//	$data = file($FILE_WORK_DIR_NAME);
	//	$count = count($data);

	//	$data_array = explode(";", $data[0]);
	//	$data_up = $data_array[0];
	//	$data_up = substr($data_up,6,4)."-".substr($data_up,3,2)."-".substr($data_up,0,2);
	//
	//	$strSql = "UPDATE `b_catalog_currency_rate` SET `DATE_RATE` = '".$data_up."', `RATE`='".$data_array[1]."' WHERE `ID`='1'";
	//	$res = $DB->Query($strSql);

	$strSql = "DELETE FROM `b_iblock_element_property` WHERE `IBLOCK_PROPERTY_ID`='14'";
	$res = $DB->Query($strSql);

	$r_db = array();
	$strSql = "
		SELECT
			b_iblock_element.ID AS element_id,
			b_iblock_element.XML_ID AS element_xml_id,
			b_iblock_element.NAME AS element_name,
			b_catalog_price.PRICE AS price,
			b_catalog_price.CURRENCY AS CURRENCY,
			b_iblock_element_property.`VALUE` as value
		FROM
			b_catalog_price,
			b_iblock_element
			LEFT JOIN `b_iblock_element_property` ON `b_iblock_element`.`ID` = `b_iblock_element_property`.`IBLOCK_ELEMENT_ID` AND b_iblock_element_property.IBLOCK_PROPERTY_ID = 16
		WHERE
			b_iblock_element.IBLOCK_ID = 4 AND
			b_catalog_price.CATALOG_GROUP_ID = 1 AND
			b_iblock_element.ID = b_catalog_price.PRODUCT_ID AND
			b_iblock_element.ACTIVE = 'Y'";
	$results = $DB->Query($strSql);

	while ($row = $results->Fetch()) {
		$r_db[(int)$row["element_xml_id"]] = $row;
	}
	unset($results);

	$a_db = array();
	$strSql = "
		SELECT
			`ID`,
			`NAME`,
			`XML_ID`
		FROM
			`b_iblock_section`
		WHERE
			IBLOCK_ID = 4
		ORDER BY
			`XML_ID`";
	$results = $DB->Query($strSql);
	while ($row = $results->Fetch()) {
		$a_db[(int)$row["XML_ID"]] = $row;
	}
	unset($results);

	$f_db = array();
	$strSql = "
		SELECT
			`ID`,
			`NAME`
		FROM
			`b_iblock_element`
		WHERE
			IBLOCK_ID = 8";
	$results = $DB->Query($strSql);
	while ($row = $results->Fetch()) {
		$f_db[$row["NAME"]] = $row["ID"];
	}
	unset($results);

	$xml = simplexml_load_file($FILE_WORK_DIR_NAME);

	foreach ($xml->shop->categories->category as $category) {

		$caption  = (string)$category;
		$xmlId    = intval($category['id']);
		$parentId = intval($category['parentId']);

		$row = $a_db[$xmlId];
		if ($row["XML_ID"] != $xmlId or $row["NAME"] != $caption) {

			$ID        = 0;
			$ACTIVE    = "Y";
			$ar_result = CIBlockSection::GetList(Array(), Array(
				"XML_ID" => $parentId,
				"IBLOCK_ID" => "4"
			));
			if ($node = $ar_result->GetNext()) {
				$ID     = $node["ID"];
				$ACTIVE = $node["ACTIVE"];
			}

			if (($ACTIVE == "Y") && ($ID or !$parentId)) {
				$bs       = new CIBlockSection;
				$arFields = Array(
					"ACTIVE" => "Y",
					"IBLOCK_SECTION_ID" => !$parentId ? 'NULL' : $ID,
					"IBLOCK_ID" => "4",
					"NAME" => $caption,
					"CODE" => translit($caption) . $xmlId,
					"XML_ID" => $xmlId
				);

				$ID        = 0;
				$ar_result = CIBlockSection::GetList(Array(), Array(
					"XML_ID" => $xmlId,
					"IBLOCK_ID" => "4"
				));
				if ($node = $ar_result->GetNext()) {
					$ID = $node["ID"];
				}

				if ($ID > 0) {
					unset($arFields["ACTIVE"]);
					$res = $bs->Update($ID, $arFields, false);
					if ($res) {
						$update_db["section_up"]++;
					}
				}
				else {
					$ID  = $bs->Add($arFields, false);
					$res = ($ID > 0);
					if ($res) {
						$update_db["section_new"]++;
					}
				}

				if (!$res) {
					echo $bs->LAST_ERROR;
				}

				unset($bs);
			}

			if (microtime(true) - $runtime > 10) {
				printf("<br>10 секунд на обновление разделов превышино<center>%.5f с</center>", microtime(true) - $runtime . "<br>");
				die();
			}
		}
	}

	printf("<br><center>%.5f с</center>", microtime(true) - $runtime . "<br>");

	$data_db = array();
	$count = 0;
	foreach ($xml->shop->offers->offer as $offer) {
		$count++;
		/*
		 * <offer id="41613" parentId="559" store="true" price1="172.28" price2="160.73" price3="149.18" price4="137.95"  currency="RUB" warranty="0,5">
		<name>Аккумулятор GP 100АААНС-BC2PET (ААА) 1000 мАч (NiMH) (2 шт. в упаковке)</name>
		</offer>
		 * */

		$caption  = (string)$offer->name;
		$xmlId    = intval($offer['id']);
		$parentId = intval($offer['parentId']);
		$price    = $offer['price4'];
		$store    = $offer['store'] == 'true' ? true : false;
		$currency = $offer['currency'] == 'RUB' ? 'RUB' : 'RUB';
		$warranty = $offer['warranty'];

		$PRICE           = $r_db[$xmlId]["price"];
		$VALUE           = $r_db[$xmlId]["value"];
		$VALUE2          = $store ? "7" : NULL;
		$data_db[$xmlId] = true;

		if ($PRICE != $price or $VALUE != $VALUE2) {

			//		echo $xmlId, '<br>';
			//
			//		echo $PRICE, '<br>';
			//		echo $price, '<br>';
			//		echo $VALUE, '<br>';
			//		echo $VALUE2, '<br><br>';

			$ID = 0;

			$ar_result = CIBlockSection::GetList(Array(), Array(
				"XML_ID" => $parentId,
				"IBLOCK_ID" => "4",
				"ACTIVE" => "Y"
			));
			if ($node = $ar_result->GetNext()) {
				$ID = $node["ID"];
			}

			$node_id = 0;

			if ($ID != 0) {
				/************************************************************/
				$MANUFACTURER_ID = false;
				//			$MANUFACTURER = trim(str_replace("\r\n","",$data_array[9]));
				//			if(isset($f_db[$MANUFACTURER]))
				//			{
				//				$MANUFACTURER_ID = $f_db[$MANUFACTURER];
				//			}
				//			elseif(!empty($MANUFACTURER))
				//			{
				//				$bs = new CIBlockElement;
				//				$arFields = Array(
				//					"ACTIVE" => "Y",
				//					"IBLOCK_ID" => "8",
				//					"IN_SECTIONS" => "N",
				//					"NAME" => $MANUFACTURER,
				//				);
				//
				//				$MANUFACTURER_ID = $bs->Add($arFields,false);
				//				$f_db[$MANUFACTURER] = $MANUFACTURER_ID;
				//				unset($bs);
				//			}
				/************************************************************/

				$PROP     = array();
				$PROP[14] = "NULL";
				$PROP[16] = $store ? "7" : "NULL";
				$PROP[19] = $xmlId;
				$PROP[21] = $MANUFACTURER_ID;
				$PROP[27] = $warranty;

				$arFields = Array(
					"MODIFIED_BY" => 1,
					"IBLOCK_SECTION_ID" => $ID,
					"IBLOCK_ID" => 4,
					"SORT" => 500,
					"PROPERTY_VALUES" => $PROP,
					"NAME" => $caption,
					"CODE" => translit($caption) . $xmlId,
					"ACTIVE" => "Y",
					"PREVIEW_TEXT" => "",
					"DETAIL_PICTURE" => "",
					"XML_ID" => $xmlId
				);

				$FILE_DIR     = $_SERVER["DOCUMENT_ROOT"] . "/" . COption::GetOptionString("main", "upload_dir", "upload") . "/download/";
				$img_filename = $FILE_DIR . $xmlId . ".jpg";
				$img_url      = "http://forum3.ru/descriptions/descr2/" . $xmlId . ".jpg";
				$img_hash     = file_get_contents($img_url);

				if ($img_hash != false) {
					if (strpos($img_hash, '<html>') === false) {
						$img_file = fopen($img_filename, "w");
						fwrite($img_file, $img_hash);
						fclose($img_file);
						$arFields['DETAIL_PICTURE'] = CFile::MakeFileArray($img_filename);
					}
				}

				$ID        = 0;
				$ar_result = CIBlockElement::GetList(Array("SORT" => "ASC"), Array(
					"XML_ID" => $xmlId,
					"IBLOCK_ID" => "4"
				));
				if ($node = $ar_result->GetNext()) {
					$ID = $node["ID"];
				}

				$el = new CIBlockElement;
				if ($ID > 0) {
					$IDE = $el->Update($ID, $arFields);
					if ($IDE) {
						$update_db["element_up"]++;
					}
				}
				else {
					$PROP[14]                    = "5";
					$arFields["PROPERTY_VALUES"] = $PROP;
					$ID                          = $el->Add($arFields);
					$IDE                         = ($ID > 0);
					if ($IDE) {
						$update_db["element_new"]++;
					}


				}

				$node_id = $ID;

				if (!$IDE) {
					echo $el->LAST_ERROR;
					echo '<pre>';
					print_r($arFields);
					echo '</pre>';
				}


				unset($el);

				if ($IDE) {
					//****************************************************************
					$ID        = 0;
					$ar_result = CCatalogProduct::GetByID($node_id);
					$ID        = $ar_result["ID"];

					$cp       = new CCatalogProduct;
					$arFields = Array(
						"ID" => $node_id,
						"VAT_INCLUDED" => "Y"
					);

					if ($ID > 0) {
						$res = $cp->Update($ID, $arFields);
					}
					else {
						$ID  = $cp->Add($arFields);
						$res = ($ID > 0);
					}

					if (!$res) {
						echo $cp->LAST_ERROR;
					}

					unset($cp);
					//****************************************************************

					$ID        = 0;
					$ar_result = CPrice::GetList(Array(), Array(
						"PRODUCT_ID" => $node_id,
						"CATALOG_GROUP_ID" => "1"
					));
					if ($node = $ar_result->GetNext()) {
						$ID = $node["ID"];
					}

					$arFields = Array(
						"PRODUCT_ID" => $node_id,
						"EXTRA_ID" => "null",
						"CATALOG_GROUP_ID" => 1,
						"PRICE" => $price,
						"CURRENCY" => $currency
					);

					$pr = new CPrice;

					if ($ID > 0) {
						$res = $pr->Update($ID, $arFields);
					}
					else {
						$ID  = $pr->Add($arFields);
						$res = ($ID > 0);
					}

					if (!$res) {
						echo $pr->LAST_ERROR;
					}

					unset($pr);
					//****************************************************************

					$ID        = 0;
					$ar_result = CPrice::GetList(Array(), Array(
						"PRODUCT_ID" => $node_id,
						"CATALOG_GROUP_ID" => "2"
					));
					if ($node = $ar_result->GetNext()) {
						$ID = $node["ID"];
					}

					$arFields = Array(
						"PRODUCT_ID" => $node_id,
						"EXTRA_ID" => 1,
						"CATALOG_GROUP_ID" => 2,
//						"PRICE" => nacenka($price),
						"PRICE" => $price * 1.10,
						"CURRENCY" => $currency
					);

					$pr = new CPrice;

					if ($ID > 0) {
						$res = $pr->Update($ID, $arFields);
					}
					else {
						$ID  = $pr->Add($arFields);
						$res = ($ID > 0);
					}

					if (!$res) {
						echo $pr->LAST_ERROR;
					}

					unset($pr);
					//****************************************************************
				}
			}


			if (microtime(true) - $runtime > 10) {
				CIBlockSection::ReSort(4);
				BXClearCache(true);

				print_r($update_db);
				echo ' ', $count, ' ';
				?>
				<script type = "text/javascript">
					setTimeout('document.location = document.location', 3000);
				</script>
				<?
				printf("<br>10 секунд на обновление товаров превышино<center>%.5f с</center>", microtime(true) - $runtime . "<br>");
				die();
			}
		}
	}

	echo '<br>memory usage: ' . memory_get_usage(true) . "<br>";

	unset($data);
	unset($r_db);
	unset($a_db);

	CIBlockSection::ReSort(4);
	$r_db = array();
	$strSql = "
		SELECT
			b_iblock_element.ID AS element_id,
			b_iblock_element.XML_ID AS element_xml_id
		FROM
			b_catalog_price,
			b_iblock_element
		WHERE
			b_iblock_element.IBLOCK_ID = 4 AND
			b_catalog_price.CATALOG_GROUP_ID = 1 and
			b_iblock_element.ID = b_catalog_price.PRODUCT_ID and
			b_iblock_element.ACTIVE = 'Y'";
	$results = $DB->Query($strSql);
	while ($row = $results->Fetch()) {
		$r_db[(int)$row["element_xml_id"]] = (int)$row["element_id"];
	}

	$arResult = array_diff_key($r_db, $data_db);
	unset($r_db);
	unset($data_db);
	foreach ($arResult as $key => $value) {
		$arFields = Array("ACTIVE" => "N");
		$el       = new CIBlockElement;
		$OK       = $el->Update($value, $arFields);

		//CIBlockElement::Delete($value['element_id']);

		unset($el);
		$update_db["element_down"]++;
	}
	unset($arResult);

	$strSql = "SELECT ID FROM b_iblock_element WHERE IBLOCK_ID = 4 ORDER BY TIMESTAMP_X DESC LIMIT 100";
	$results = $DB->Query($strSql);
	while ($row = $results->Fetch()) {
		$strSql = "INSERT INTO `b_iblock_element_property`(`IBLOCK_PROPERTY_ID`,`IBLOCK_ELEMENT_ID`,`VALUE`,`VALUE_TYPE`,`VALUE_ENUM`) VALUES ( '14'," . $row["ID"] . ",'5','text','5')";
		$res    = $DB->Query($strSql);
	}

	BXClearCache(true);

	print_r($update_db);
	printf("<br><center>%.5f с</center>", microtime(true) - $runtime . "<br>");
	echo '<br>memory usage: ' . memory_get_usage(true) . "<br>";

	$out2 = ob_get_contents();
	ob_end_clean();

	if ($_GET['log']) {
		echo $out2;
	}
?>