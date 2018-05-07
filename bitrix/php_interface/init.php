<?php
/**
 * Created by PhpStorm.
 * User: ZeK
 * Date: 02.05.2018
 * Time: 19:31
 */

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("Handlers", "OnBeforeIBlockElementAddHandler"));

AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("Handlers", "OnAfterIBlockElementAddHandler"));

class Handlers
{
    // создаем обработчик события "OnBeforeIBlockElementAdd"
    function OnBeforeIBlockElementAddHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == 1) {

            $arFields['NAME'] = mb_strtoupper($arFields['NAME']);
        }
    }

    // создаем обработчик события "OnAfterIBlockElementAdd"
    function OnAfterIBlockElementAddHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == 1 && !empty($arFields['RESULT'])) {

            $el_res= CIBlockElement::GetByID( $arFields['RESULT'] );
            if( $el_arr = $el_res->GetNext()){
                $arFields['DETAIL_PAGE_URL'] = $el_arr['DETAIL_PAGE_URL'];
            }

//             Отправляем в pushall
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => "https://pushall.ru/api.php",
                CURLOPT_POSTFIELDS => array(
                    "type" => "broadcast",
                    "id" => "4328",
                    "key" => "b06eaae0f5ed80d30bc572f9983186db",
                    "text" => $arFields['PREVIEW_TEXT'],
                    "title" => $arFields['NAME'],
                    "url" => 'http://likehack.ru' . $arFields['DETAIL_PAGE_URL']
                ),
                CURLOPT_SAFE_UPLOAD => true,
                CURLOPT_RETURNTRANSFER => true
            ));
            $return=json_decode(curl_exec($ch)); //получить ответ или ошибку
            curl_close($ch);
        }
    }
}