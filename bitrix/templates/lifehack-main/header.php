<!DOCTYPE html>
<html lang="<?= $APPLICATION->GetLang()['LANGUAGE_ID'] ?>">

<head>
    <?
    $APPLICATION->SetTitle($APPLICATION->GetTitle(true));

    $APPLICATION->SetAdditionalCSS($APPLICATION->GetTemplatePath('css/reset.css'));
    $APPLICATION->SetAdditionalCSS($APPLICATION->GetTemplatePath('styles.css'));

    // JS

    $APPLICATION->SetAdditionalCSS("/bitrix/css/main/bootstrap.css");
    $APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
    Bitrix\Main\Page\Asset::getInstance()->addJs($APPLICATION->GetTemplatePath('js/jquery-3.3.1.min.js'));
    Bitrix\Main\Page\Asset::getInstance()->addJs($APPLICATION->GetTemplatePath('js/bootstrap/bootstrap.min.js'));
    Bitrix\Main\Page\Asset::getInstance()->addJs($APPLICATION->GetTemplatePath('js/script.js'));
    Bitrix\Main\Page\Asset::getInstance()->addJs($APPLICATION->GetTemplatePath('js/column.js'));


    $APPLICATION->ShowHead();


    ?>

    <!--[if lt IE 7]>
    <link rel="stylesheet" href="<?=$APPLICATION->GetTemplatePath('css/ie/ie6.css');?>" type="text/css" media="screen">
    <script type="text/javascript" src="<?=$APPLICATION->GetTemplatePath('js/ie_png.js');?>"></script>
    <script type="text/javascript">
        ie_png.fix('.png, footer, header nav ul li a, .nav-bg, .list li img');
    </script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?=$APPLICATION->GetTemplatePath('js/html5.js');?>"></script>
    <![endif]-->
    <link href="https://fonts.googleapis.com/css?family=Kurale" rel="stylesheet">
</head>
<body>
<div id="top-panel"><?= $APPLICATION->ShowPanel(); ?></div>
<div class="nav-collapse collapse ">
    <div class="" style="margin: 10px; height: 40px;">
    <form action="/search/">
        <div class="col-xs-12">
            <div class="input-group">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:search.suggest.input",
                    "search.input-default",
                    array(
                        "NAME" => "q",
                        "VALUE" => $arResult["REQUEST"]["~QUERY"],
                        "INPUT_SIZE" => -1,
                        "DROPDOWN_SIZE" => 10,
                        "FILTER_MD5" => $arResult["FILTER_MD5"],
                    ),
                    $component, array("HIDE_ICONS" => "Y")
                ); ?>
                <span class="input-group-btn">
                                      <button class="btn btn-primary" type="submit">Искать</button>
                                    </span>
            </div>
        </div>
    </form>
    </div>
    <div class="clear"></div>
    <? $APPLICATION->IncludeComponent("bitrix:menu", "menu_left", Array(
        "COMPONENT_TEMPLATE" => "catalog_vertical",
        "ROOT_MENU_TYPE" => "left",    // Тип меню для первого уровня
        "MENU_THEME" => "blue",    // Тема меню
        "MENU_CACHE_TYPE" => "A",    // Тип кеширования
        "MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
        "MENU_CACHE_USE_GROUPS" => "N",    // Учитывать права доступа
        "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
        "MAX_LEVEL" => "1",    // Уровень вложенности меню
        "CHILD_MENU_TYPE" => "left",    // Тип меню для остальных уровней
        "USE_EXT" => "Y",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
        "DELAY" => "N",    // Откладывать выполнение шаблона меню
        "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
    ),
        false
    ); ?>
</div>
<div class="show-xs show-sm btn-menu-mobile hidden-md hidden-lg">
    <div class="navbar  show-xs show-sm btn-menu-mobile" style="">
        <a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" style="">
            <span class="fa fa-bars" style=""></span>
        </a>

        <!-- Be sure to leave the brand out there if you want it shown -->


        <!-- Everything you want hidden at 940px or less, place within here -->

    </div>
</div>    <!-- Everything you want hidden at 940px or less, place within here -->

</div>
<div class="wrap">
    <!-- header -->
    <header>
        <div class="container">

            <div class="">
                <div class="col-xs-3 hidden-xs hidden-sm">
                    <a href="/"><img src="<?= $APPLICATION->GetTemplatePath('images/lh_logo.png') ?>"
                                     class=" img-responsive logo"/></a>
                </div>
                <div class="col-md-6 hidden-xs hidden-sm">
                    <form action="/search/">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:search.suggest.input",
                                    "search.input-default",
                                    array(
                                        "NAME" => "q",
                                        "VALUE" => $arResult["REQUEST"]["~QUERY"],
                                        "INPUT_SIZE" => -1,
                                        "DROPDOWN_SIZE" => 10,
                                        "FILTER_MD5" => $arResult["FILTER_MD5"],
                                    ),
                                    $component, array("HIDE_ICONS" => "Y")
                                ); ?>
                                <span class="input-group-btn">
                                      <button class="btn btn-primary" type="submit">Искать</button>
                                    </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-3 col-xs-12 bg-container">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:system.auth.form",
                        "main",
                        array(
                            "FORGOT_PASSWORD_URL" => "/personal/",
                            "PROFILE_URL" => "/personal/",
                            "REGISTER_URL" => "/personal/register/",
                            "SHOW_ERRORS" => "N",
                            "COMPONENT_TEMPLATE" => "main"
                        ),
                        false
                    ); ?>
                </div>
            </div>
        </div>

    </header>
    <div class="container">
        <div class="row">
        <!-- aside -->
        <aside class="col-xs-3 hidden-xs hidden-sm" style="margin-top: 10px;">
            <? $APPLICATION->IncludeComponent("bitrix:menu", "menu_left", Array(
                "COMPONENT_TEMPLATE" => "catalog_vertical",
                "ROOT_MENU_TYPE" => "left",    // Тип меню для первого уровня
                "MENU_THEME" => "blue",    // Тема меню
                "MENU_CACHE_TYPE" => "A",    // Тип кеширования
                "MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
                "MENU_CACHE_USE_GROUPS" => "N",    // Учитывать права доступа
                "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
                "MAX_LEVEL" => "1",    // Уровень вложенности меню
                "CHILD_MENU_TYPE" => "left",    // Тип меню для остальных уровней
                "USE_EXT" => "Y",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
                "DELAY" => "N",    // Откладывать выполнение шаблона меню
                "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
            ),
                false
            ); ?>
            <!--<form action="" id="newsletter-form">
                <fieldset>
                    <div class="rowElem">
                        <h2>Подписаться</h2>
                        <input type="email" value="Введите ваш email"
                               onFocus="if(this.value=='Введите ваш email'){this.value=''}"
                               onBlur="if(this.value==''){this.value='Введите ваш email'}">
                        <div class="clear"><a href="#" class="fleft">отписаться</a>
                            <a href="#" class="fright"
                               onClick="document.getElementById('newsletter-form').submit()">Отправить</a>
                        </div>
                    </div>
                </fieldset>
            </form>-->

        </aside>
        <div style="height: 15px"></div>
        <!-- content -->
        <section class="col-xs-12 col-md-9">
