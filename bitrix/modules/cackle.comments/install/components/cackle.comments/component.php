<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;
define('CACKLE_TIMER', 500);
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/mainpage.php");

//$site_name=
//global $USER;

function cackle_cackle_auth() {
    global $USER;
    $cackle_api = new CackleAPI();
    $siteApiKey = $cackle_api->cackle_get_param("site_api_".$_SERVER['HTTP_HOST']);
    $timestamp = time();
    $arUser = CUser::GetByID($USER->GetID());

    if ($res = $arUser->Fetch()) {
        $res["LAST_NAME"]= (strtoupper(SITE_CHARSET) != "UTF-8") ? iconv(SITE_CHARSET, "utf-8", $res["LAST_NAME"]) : $res["LAST_NAME"];
        $res["NAME"]= (strtoupper(SITE_CHARSET) != "UTF-8") ? iconv(SITE_CHARSET, "utf-8", $res["NAME"]) : $res["NAME"];
        $res["SECOND_NAME"]= (strtoupper(SITE_CHARSET) != "UTF-8") ? iconv(SITE_CHARSET, "utf-8", $res["SECOND_NAME"]) : $res["SECOND_NAME"];
        $userFullNameParts = array($res["LAST_NAME"], $res["NAME"], $res["SECOND_NAME"]);
        if(strlen($res["LAST_NAME"])>1 || strlen($res["NAME"])>1){
            $username = implode(" ", $userFullNameParts);
        }
        else{
            $username = $res["LOGIN"];
        }
        $user = array(
            'id' => $res["ID"],
            'name' => $username,
            'email' => $res["EMAIL"],
            'avatar' => $_SERVER['HOST'] . CFile::GetPath($res["PERSONAL_PHOTO"]),
        );
        $user_data = base64_encode(json_encode($user));
    } else {
        $user = '{}';
        $user_data = base64_encode($user);
    }
    $sign = md5($user_data . $siteApiKey . $timestamp);
    return "$user_data $sign $timestamp";
}




function cackle_time_is_over($cron_time){
    $cackle_api = new CackleAPI();
    $get_last_time = $cackle_api->cackle_get_param("last_time_".$_SERVER['HTTP_HOST']);
    $now=time();
    if ($get_last_time==""){
        $q="last_time_".$_SERVER['HTTP_HOST'];
        $set_time = $cackle_api->cackle_set_param($q,$now);
        return time();
    }
    else{
        if($get_last_time + $cron_time > $now){
            return false;
        }
        if($get_last_time + $cron_time < $now){
            $q="last_time_".$_SERVER['HTTP_HOST'];
            $set_time = $cackle_api->cackle_set_param($q,$now);
            return $cron_time;
        }
    }
}

function cackle_get_offset(){
    $id = isset($_GET['cacklepage']) ? (int)$_GET['cacklepage'] : 0;
	$size = 100;
	return $size*$id;
}

function cackle_get_pages($post_id){
		
    $size = 100;
    $cackle_api = new CackleAPI();

    $sql = "select count(*) as c from ".PREFIX."_comments where post_id = '$post_id' and approve = 1";
		
	$get_all_comments_count = $cackle_api->db_connect($sql);
	
	return $pages = $get_all_comments_count[0]['c']/$size;
	


}
function cackle_get_local_comments($post_id){
    //getting all comments for special post_id from database.
    $size = 100;
    $cackle_api = new CackleAPI();
	
	$offset = cackle_get_offset();
    //$sql = "select * from ".PREFIX."_comments where post_id = '$post_id' and approve = 1";
	$sql = "select * from ".PREFIX."_comments where post_id = '$post_id' and approve = 1 limit $size offset " . $offset;
	
    $get_all_comments = $cackle_api->db_connect($sql);
	
    return $get_all_comments;
}


if (CModule::IncludeModule("cackle.comments")){
    $arResult = array();
    $cackle_api = new CackleAPI();
    $arResult['SITE_ID'] = $cackle_api->cackle_get_param("site_id_".$_SERVER['HTTP_HOST']);
    if($arParams['CHANNEL_ID']=='URL'){
        $arResult['MC_CHANNEL'] = $APPLICATION->GetCurPage();
    }
	else if(isset($arParams['CHANNEL_ID'])){
        $arResult['MC_CHANNEL'] = $arParams['CHANNEL_ID'];
	}
	else{
		$arResult['MC_CHANNEL'] = $APPLICATION->GetCurPage();
	}
    //var_dump($arResult['MC_CHANNEL']);

    $arResult['CACKLE_OBJ'] = cackle_get_local_comments($arResult['MC_CHANNEL']);
    if ((strtolower($DB->type)=="mysql")){
        $sync = new Sync();
        if (cackle_time_is_over(CACKLE_TIMER)){
            //print_r('test');
            $sync->init();
        }
    }
	$arResult['PAGGING'] = $arParams['PAGGING'];
    $arResult['SSO'] = cackle_cackle_auth();
	$arResult['PAGES'] = cackle_get_pages($arResult['MC_CHANNEL']);
	//print_r($arResult['PAGES']);
	
    $arResult['SSO_PARAM'] = $cackle_api->cackle_get_param('cackle_sso_'.$_SERVER['HTTP_HOST']);
    $this->IncludeComponentTemplate();
}

?>