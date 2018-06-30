<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Поиск по сайту");
$APPLICATION->SetPageProperty("ROBOTS", "NOINDEX,NOFOLLOW");
?><? $APPLICATION->IncludeComponent("bitrix:search.page", "search.page_main", Array(
    "AJAX_MODE" => "N",    // Включить режим AJAX
    "AJAX_OPTION_ADDITIONAL" => "",    // Дополнительный идентификатор
    "AJAX_OPTION_HISTORY" => "Y",    // Включить эмуляцию навигации браузера
    "AJAX_OPTION_JUMP" => "Y",    // Включить прокрутку к началу компонента
    "AJAX_OPTION_STYLE" => "Y",    // Включить подгрузку стилей
    "CACHE_TIME" => "3600",    // Время кеширования (сек.)
    "CACHE_TYPE" => "A",    // Тип кеширования
    "CHECK_DATES" => "N",    // Искать только в активных по дате документах
    "COLOR_NEW" => "ff7b01",    // Цвет позднего тега (пример: "C0C0C0")
    "COLOR_OLD" => "337ab7",    // Цвет раннего тега (пример: "FEFEFE")
    "COLOR_TYPE" => "Y",    // Плавное изменение цвета
    "DEFAULT_SORT" => "rank",    // Сортировка по умолчанию
    "DISPLAY_BOTTOM_PAGER" => "Y",    // Выводить под результатами
    "DISPLAY_TOP_PAGER" => "N",    // Выводить над результатами
    "FILTER_NAME" => "",    // Дополнительный фильтр
    "FONT_MAX" => "50",    // Максимальный размер шрифта (px)
    "FONT_MIN" => "10",    // Минимальный размер шрифта (px)
    "NO_WORD_LOGIC" => "N",    // Отключить обработку слов как логических операторов
    "PAGER_SHOW_ALWAYS" => "Y",    // Выводить всегда
    "PAGER_TEMPLATE" => "",    // Название шаблона
    "PAGER_TITLE" => "Результаты поиска",    // Название результатов поиска
    "PAGE_RESULT_COUNT" => "40",    // Количество результатов на странице
    "PERIOD_NEW_TAGS" => "",    // Период, в течение которого считать тег новым (дней)
    "RESTART" => "Y",    // Искать без учета морфологии (при отсутствии результата поиска)
    "SHOW_CHAIN" => "Y",    // Показывать цепочку навигации
    "SHOW_ITEM_DATE_CHANGE" => "N",    // Показывать дату изменения документа
    "SHOW_ITEM_TAGS" => "Y",    // Показывать теги документа
    "SHOW_ORDER_BY" => "Y",    // Показывать сортировку
    "SHOW_TAGS_CLOUD" => "N",    // Показывать облако тегов
    "SHOW_WHEN" => "N",    // Показывать фильтр по датам
    "SHOW_WHERE" => "N",    // Показывать выпадающий список "Где искать"
    "TAGS_INHERIT" => "Y",    // Сужать область поиска
    "TAGS_PAGE_ELEMENTS" => "150",    // Количество тегов
    "TAGS_PERIOD" => "",    // Период выборки тегов (дней)
    "TAGS_SORT" => "CNT",    // Сортировка тегов
    "TAGS_URL_SEARCH" => "",    // Путь к странице поиска (от корня сайта)
    "USE_LANGUAGE_GUESS" => "Y",    // Включить автоопределение раскладки клавиатуры
    "USE_SUGGEST" => "Y",    // Показывать подсказку с поисковыми фразами
    "USE_TITLE_RANK" => "Y",    // При ранжировании результата учитывать заголовки
    "SHOW_RATING" => "Y",    // При ранжировании результата учитывать заголовки
    "WIDTH" => "100%",    // Ширина облака тегов (пример: "100%" или "100px", "100pt", "100in")
    "arrFILTER" => array(    // Ограничение области поиска
        0 => "no",
    ),
    "arrFILTER_iblock_blog" => array(
        0 => "all",
    ),
    "arrWHERE" => ""
),
    false
); ?><br><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>