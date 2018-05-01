<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мне понравилось");
?><?$APPLICATION->IncludeComponent("bitrix:asd.favorite.likes", "favorite-default", Array(
	"ALLOW_MOVED" => "Y",	// Позволять перемещать элементы
		"COMPONENT_TEMPLATE" => ".default",
		"FAV_TYPE" => "articles",	// Тип избранного
		"FOLDER_ID" => "",	// ID папки
		"NOT_SHOW_WITH_NOT_FOLDER" => "N",	// Не выводить элементы, если не задан ID папки
		"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
		"PAGE_COUNT" => "",	// Количество на странице
		"PREVIEW_HEIGHT" => "50",	// Высота превью
		"PREVIEW_WIDTH" => "50",	// Ширина превью
		"USER_ID" => "",	// ID пользователя (текущий по умол.)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>