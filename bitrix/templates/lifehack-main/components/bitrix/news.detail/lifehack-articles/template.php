<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$this->addExternalCss("/bitrix/css/main/bootstrap.css");
$this->addExternalCss("/bitrix/css/main/font-awesome.css");
$this->addExternalCss($this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css');
CUtil::InitJSCore(array('fx'));
?>

<?
if(CModule::IncludeModule('asd.favorite')) {
    foreach($arResult["ITEMS"] as $cell=>$arElement){
        $arIDs[] = $arElement['ID'];
    }
    $arFavs = CASDfavorite::GetLikesAndFavedByElementID($arIDs);
}
?>

<div class="bx-newsdetail">
    <div class="bx-newsdetail-block" id="<? echo $this->GetEditAreaId($arResult['ID']) ?>">
        <div class="bx-newslist-show-counter"><i
                    class="fa fa-eye"></i> <?= GetMessage("IBLOCK_FIELD_SHOW_COUNTER") ?>:
            <span class="yellow-text"><?= intval($arResult["FIELDS"]['SHOW_COUNTER']); ?></span>
            <?$APPLICATION->IncludeComponent("bitrix:asd.favorite.button", "main", array (
                "FAV_TYPE" => "articles",
                "BUTTON_TYPE" => "lik",
                "ELEMENT_ID" => $arResult["ID"],
                "GET_COUNT_AFTER_LOAD" => "N",
                "SET_COUNT" => $arResult[$arResult["ID"]]["COUNT"],
                "FAVED" => $arFavs[$arResult["ID"]]["FAVED"]
            ))?>
        </div>
        <? if ($arParams["USE_RATING"] == "Y"): ?>
            <div class="col-xs-7 text-right blog-post-rating">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:iblock.vote",
                    "flat",
                    Array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "ELEMENT_ID" => $arResult["ID"],
                        "MAX_VOTE" => $arParams["MAX_VOTE"],
                        "VOTE_NAMES" => $arParams["VOTE_NAMES"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
                        "SHOW_RATING" => "N",
                    ),
                    $component
                ); ?>
            </div>
        <? endif ?>
        <? if ($arParams["DISPLAY_PICTURE"] != "N"): ?>
            <? if (!empty($arResult['PROPERTIES']['video_youtube']['VALUE'])): ?>
                <div class="bx-newsdetail-img">
                    <?$APPLICATION->IncludeComponent(
                        "L1:youtube",
                        "",
                        Array(
                            "Autoplay" => "0",
                            "CC" => "0",
                            "Control" => "1",
                            "FULL" => "1",
                            "H" => "480",
                            "IMG" => $arResult["DETAIL_PICTURE"]["SRC"],
                            "IV" => "0",
                            "LOGO" => "0",
                            "LOOP" => "0",
                            "URL" => $arResult['PROPERTIES']['video_youtube']['VALUE'],
                            "W" => "100%"
                        )
                    );?>
                </div>
            <? elseif ($arResult["VIDEO"]): ?>
                <div class="bx-newsdetail-youtube embed-responsive embed-responsive-16by9" style="display: block;">
                    <iframe src="<? echo $arResult["VIDEO"] ?>" frameborder="0" allowfullscreen=""></iframe>
                </div>
            <? elseif ($arResult["SOUND_CLOUD"]): ?>
                <div class="bx-newsdetail-audio">
                    <iframe width="100%" height="166" scrolling="no" frameborder="no"
                            src="https://w.soundcloud.com/player/?url=<? echo urlencode($arResult["SOUND_CLOUD"]) ?>&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>
                </div>
            <? elseif ($arResult["SLIDER"] && count($arResult["SLIDER"]) > 1): ?>
                <div class="bx-newsdetail-slider">
                    <div class="bx-newsdetail-slider-container"
                         style="width: <? echo count($arResult["SLIDER"]) * 100 ?>%;left: 0%;">
                        <? foreach ($arResult["SLIDER"] as $file): ?>
                            <div style="width: <? echo 100 / count($arResult["SLIDER"]) ?>%;"
                                 class="bx-newsdetail-slider-slide">
                                <img src="<?= $file["SRC"] ?>" alt="<?= $file["DESCRIPTION"] ?>">
                            </div>
                        <? endforeach ?>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="bx-newsdetail-slider-arrow-container-left">
                        <div class="bx-newsdetail-slider-arrow"><i class="fa fa-angle-left"></i></div>
                    </div>
                    <div class="bx-newsdetail-slider-arrow-container-right">
                        <div class="bx-newsdetail-slider-arrow"><i class="fa fa-angle-right"></i></div>
                    </div>
                    <ul class="bx-newsdetail-slider-control">
                        <? foreach ($arResult["SLIDER"] as $i => $file): ?>
                            <li rel="<?= ($i + 1) ?>" <? if (!$i) echo 'class="current"' ?>><span></span></li>
                        <? endforeach ?>
                    </ul>
                </div>
            <? elseif ($arResult["SLIDER"]): ?>
                <div class="bx-newsdetail-img">
                    <img
                            src="<?= $arResult["SLIDER"][0]["SRC"] ?>"
                            width="<?= $arResult["SLIDER"][0]["WIDTH"] ?>"
                            height="<?= $arResult["SLIDER"][0]["HEIGHT"] ?>"
                            alt="<?= $arResult["SLIDER"][0]["ALT"] ?>"
                            title="<?= $arResult["SLIDER"][0]["TITLE"] ?>"
                    />
                </div>
            <? elseif (is_array($arResult["DETAIL_PICTURE"])): ?>
                <div class="bx-newsdetail-img">
                    <img
                            src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>"
                            width="<?= $arResult["DETAIL_PICTURE"]["WIDTH"] ?>"
                            height="<?= $arResult["DETAIL_PICTURE"]["HEIGHT"] ?>"
                            alt="<?= $arResult["DETAIL_PICTURE"]["ALT"] ?>"
                            title="<?= $arResult["DETAIL_PICTURE"]["TITLE"] ?>"
                    />
                </div>
            <? endif; ?>
        <? endif ?>


        <div class="bx-newsdetail-content bg-container">
            <? if ($arParams["DISPLAY_NAME"] != "N" && $arResult["NAME"]): ?>
                <h1 class="bx-newsdetail-title blog-post-title"><?= $arResult["NAME"] ?></h1>
            <? endif; ?>
            <? if ($arResult["NAV_RESULT"]): ?>
                <? if ($arParams["DISPLAY_TOP_PAGER"]): ?><?= $arResult["NAV_STRING"] ?><br/><? endif; ?>
                <? echo $arResult["NAV_TEXT"]; ?>
                <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?><br/><?= $arResult["NAV_STRING"] ?><? endif; ?>
            <? elseif (strlen($arResult["DETAIL_TEXT"]) > 0): ?>
                <? echo $arResult["DETAIL_TEXT"]; ?>
            <? else: ?>
                <? echo $arResult["PREVIEW_TEXT"]; ?>
            <? endif ?>

            <div class="clear"></div>
            <br/>
            <div class="bx-newsdetail-tags">
                <?= $arResult["FIELDS"]['TAGS']; ?>
            </div>
            <div class="clear"></div>
            <br />
            <div class="social-link">
            <noindex>
                <?
                $APPLICATION->IncludeComponent("bitrix:main.share", $arParams["SHARE_TEMPLATE"], array(
                    "HANDLERS" => $arParams["SHARE_HANDLERS"],
                    "PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
                    "PAGE_TITLE" => $arResult["~NAME"],
                    "SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
                    "SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
                    "HIDE" => $arParams["SHARE_HIDE"],
                ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                );
                ?>
            </noindex>
            </div>
            <div class="source-link">
                <?=(!empty($arResult['PROPERTIES']['link']['VALUE']))? $arResult['PROPERTIES']['link']['VALUE'] : ''?>
            </div>

        </div>


        <? if ($arParams["DISPLAY_DATE"] != "N" && $arResult["DISPLAY_ACTIVE_FROM"]): ?>
            <div class="bx-newsdetail-date"><i class="fa fa-calendar-o"></i> <? echo $arResult["DISPLAY_ACTIVE_FROM"] ?>
            </div>
        <? endif ?>

        <?$APPLICATION->IncludeComponent(
            "cackle.comments",
            "",
            Array(),
            false
        );?>

    </div>
</div>
<div class="clear"></div>