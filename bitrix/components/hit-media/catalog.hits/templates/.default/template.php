<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
} ?>

<!--<pre>--><?// print_r($arResult) ?><!--</pre>-->
<h1>Горячие предложения</h1>
<div class = "clearfix">
	<? foreach ($arResult['ITEMS'] as $key => $tovar): ?>
		<? $img = Cfile::ResizeImageGet($tovar['DETAIL_PICTURE'], array(
			'width' => 165,
			"height" => 165
		));
		$last   = $key == 2 ? "last" : "";
		?>
		<div class = "tovar-item <?=$last?>"><a class = "category box-round-5" href = "#"><?= $tovar['NAME'] ?></a>

			<div class = "tovar-box1">
				<a href = "<?=$tovar['DETAIL_PAGE_URL']?>"><img src = "<?= $img['src'] ?>"></a></div>
			<a href = "<?=$tovar['DETAIL_PAGE_URL']?>" class = "tov-name-item"><?= $tovar['NAME'] ?></a>

			<div class = "tovar-cena"><?= str_replace("руб", "", CurrencyFormat($tovar['PRICES']['PRICE'], $tovar['PRICES']['CURRENCY']))?><span>руб.</span> </div>
			<a href = "<?=$tovar['DETAIL_PAGE_URL']?>?catalog_action=ADD2BASKET&id=<?=$tovar['ID']?>" class = "kupit
			btn-blue">Купить</a></div>
	<? endforeach ?>
</div>
