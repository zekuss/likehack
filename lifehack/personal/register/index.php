<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?>
    <div class="bg-container text-block">
        <? $APPLICATION->IncludeComponent(
	"bitrix:main.register", 
	"register-default", 
	array(
		"AUTH" => "Y",
		"REQUIRED_FIELDS" => array(
			0 => "EMAIL",
			1 => "NAME",
			2 => "PERSONAL_GENDER",
			3 => "PERSONAL_PHONE",
			4 => "PERSONAL_CITY",
			5 => "PERSONAL_COUNTRY",
		),
		"SET_TITLE" => "Y",
		"SHOW_FIELDS" => array(
			0 => "EMAIL",
			1 => "NAME",
			2 => "LAST_NAME",
			3 => "PERSONAL_GENDER",
			4 => "PERSONAL_BIRTHDAY",
			5 => "PERSONAL_PHOTO",
			6 => "PERSONAL_PHONE",
			7 => "PERSONAL_CITY",
			8 => "PERSONAL_COUNTRY",
		),
		"SUCCESS_PAGE" => "/personal/",
		"USER_PROPERTY" => array(
		),
		"USER_PROPERTY_NAME" => "",
		"USE_BACKURL" => "Y",
		"COMPONENT_TEMPLATE" => "register-default"
	),
	false
); ?>
    </div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>