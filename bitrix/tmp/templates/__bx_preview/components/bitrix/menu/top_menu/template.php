<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <nav>
        <ul>
            <?
            foreach ($arResult as $k => $arItem):
                $k++;
                if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                    continue;
                ?>
                <? if ($arItem["SELECTED"]): ?>
                <li class="current"><a href="<?= $arItem["LINK"] ?>" class="m<?= $k ?>" ><?= $arItem["TEXT"] ?></a></li>
            <? else: ?>
                <li><a href="<?= $arItem["LINK"] ?>" class="m<?= $k ?>" ><?= $arItem["TEXT"] ?></a></li>
            <? endif ?>

            <? endforeach ?>
        </ul>
    </nav>
<? endif ?>

