<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
} ?>

<div class = "cfix">
	<div class = "path-kroshki"><a href = "/">Главная</a>
		<? foreach ($arResult["SECTION"]["PATH"] as $key => $arPach): ?>
			&#8594; <a href = "<?= $arPach["SECTION_PAGE_URL"] ?>"><?= $arPach["NAME"] ?></a>
		<? endforeach ?>
	</div>
	<h1><?= $arResult["NAME"] ?></h1>

	<div id = "mini-opis">
		<? if (isset($arResult["DISPLAY_PROPERTIES"]["MANUFACTURER"]["DISPLAY_VALUE"])): ?>
			<p>Производитель: <?= $arResult["DISPLAY_PROPERTIES"]["MANUFACTURER"]["DISPLAY_VALUE"] ?></p>
		<? endif ?>
		<?
			$PRINT_VALUE = "";
			if (count($arResult["PRICES"]) > 0):
				foreach ($arResult["PRICES"] as $code => $arPrice):
					if ($arPrice["CAN_ACCESS"]):
						if ($arPrice["DISCOUNT_VALUE"]) {
							$PRINT_VALUE = $arPrice["PRINT_VALUE"];
						}
					endif;
				endforeach;

				if (!$arResult["PROPERTIES"]["PRESENCE"]["VALUE_ENUM_ID"]) {
					$arResult["CAN_BUY"] = false;
				}
			endif;
		?>
		<? if (!$arResult["CAN_BUY"]): ?>
			<p class = "clearfix"><img src = "<?= SITE_TEMPLATE_PATH ?>/images/cross.gif" width = "16" height = "16" align = "left">
				<span class = "net-nasklade">Нет на складе</span>
			</p>
		<? endif; ?>

		<div class = "clearfix">
			<? if (!empty($PRINT_VALUE)): ?>
				<div class = "tovar-cena"><?= preg_replace('/([*0-9 ]+)([^w]+)/i', '$1 <span>$2</span>', $PRINT_VALUE) ?></div>
			<? endif; ?>

			<? if ($arResult["CAN_BUY"]): ?>
				<a href = "<? echo $arResult["ADD_URL"] ?>" class = "kupit btn-blue" rel = "nofollow"><? echo GetMessage("CATALOG_BUY") ?></a>
			<? endif ?>
		</div>
		<p class = "clearfix">
			<? if ($arResult["PROPERTIES"]["WARRANTY"]["VALUE"]): ?>
				<b>Гарантия: </b><?= $arResult["PROPERTIES"]["WARRANTY"]["VALUE"] ?> мес.<br>
			<? endif; ?>
			<b>Артикул: </b><?= $arResult["XML_ID"] ?>
		</p>
	</div>

	<div class = "tovar-img">
		<? if (is_array($arResult["DETAIL_PICTURE"])): ?>
			<a class = "img-zoom1" href = "<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>"><img border = "0" src = "<?= $arResult["PREVIEW_IMG"]["SRC"] ?>" width = "<?= $arResult["PREVIEW_IMG"]["WIDTH"] ?>" height = "<?= $arResult["PREVIEW_IMG"]["HEIGHT"] ?>" alt = "<?= $arResult["NAME"] ?>" title = "<?= $arResult["NAME"] ?>" id = "catalog_list_image_<?= $arResult['ID'] ?>"/></a>
			<a class = "img-zoom2" href = "<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>"><img src = "<?= SITE_TEMPLATE_PATH ?>/images/zoom_03.gif" width = "25" height = "25"></a>
		<? else: ?>
			<a href = "#"><img src = "<?= SITE_TEMPLATE_PATH ?>/images/tovar1.jpg" width = "210" height = "165"></a>
		<?endif; ?>
	</div>
</div>
<?
	$adress = 'http://forum3.ru/?cmd=show_tovar&code=' . $arResult["XML_ID"];
	$html = file_get_contents($adress);
//	$a = explode('<table>',$html);
//	$a = explode('</table>',$a[1]);
//	$text = iconv("windows-1251", "utf-8", $a[0]);
//	echo $text;

	preg_match_all("/<tr>(.*)<\/tr>/isU", str_replace(array(
		"\n",
		"<br/>",
		"\r",
		"\t"
	), array(
		"",
		"",
		"",
		""
	), iconv("windows-1251", "utf-8", $html)), $nodes);

	foreach ($nodes[1] as $vol) {
		preg_match_all("/<th>(.*)<\/th>/sU", $vol, $descriptions);
		preg_match_all("/<td><li(.*)>(.*)<\/td>/sU", $vol, $values);
		$pars_result[] = array(
			'des' => $descriptions[1][0],
			'vol' => $values[2]
		);
	}
	$str = '';

	foreach ($pars_result as $key => $vol) {

		$class     = $key == 0 ? " first" : "";
		$number    = $key &1 ? "2" : "1";
		$box_round = $key & 1 ? "" : "box-round-5";
		$str .= "<div class='opis-box{$number} {$box_round} {$class}'>";
		$str .= "<div class='opis-right'>{$vol['vol'][0]}</div>{$vol['des']}</div>";
	}

?>
<?= $str ?>
<!--<pre style = "text-align: left">--><?// print_r($pars_result) ?><!--</pre>-->


<? if ($arResult["PREVIEW_TEXT"]): ?>
	<?= $arResult["PREVIEW_TEXT"] ?>
<? endif; ?>
<div class = "hr-line2"></div>
Наиболее полная и точная информация о товаре размещается на официальном сайте компании производителя. Пожалуйста, учитывайте, что внешний вид, комплектация и потребительские свойства могут быть изменены производителем без предварительного уведомления.

<script type = "text/javascript">
	$(function () {
		$('.tovar-img a.img-zoom1').lightBox({
			imageLoading: '<?=SITE_TEMPLATE_PATH?>/images/lightbox-ico-loading.gif',
			imageBtnClose: '<?=SITE_TEMPLATE_PATH?>/images/lightbox-btn-close.gif',
			imageBtnPrev: '<?=SITE_TEMPLATE_PATH?>/images/lightbox-btn-prev.gif',
			imageBtnNext: '<?=SITE_TEMPLATE_PATH?>/images/lightbox-btn-next.gif'
		});
		$('.tovar-img a.img-zoom2').lightBox({
			imageLoading: '<?=SITE_TEMPLATE_PATH?>/images/lightbox-ico-loading.gif',
			imageBtnClose: '<?=SITE_TEMPLATE_PATH?>/images/lightbox-btn-close.gif',
			imageBtnPrev: '<?=SITE_TEMPLATE_PATH?>/images/lightbox-btn-prev.gif',
			imageBtnNext: '<?=SITE_TEMPLATE_PATH?>/images/lightbox-btn-next.gif'
		});
	});
</script>