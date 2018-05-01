<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");

?><div class="bg-container text-block">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.profile",
	"profile-default",
Array()
);?>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>