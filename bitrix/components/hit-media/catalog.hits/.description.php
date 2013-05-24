<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Хиты продаж",
	"DESCRIPTION" => "Выводит несколько случайных товаров",
	"ICON" => "/images/catalog.hits.gif",
	"PATH" => array(
		"ID" => "hits",
		"CHILD" => array(
			"ID" => "Hit-media",
			"NAME" => "Hit-media"
		)
	),
);

?>
