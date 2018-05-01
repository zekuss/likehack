<?php
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/mainpage.php");



if (LANG_CHARSET=='windows-1251'){
    header('Content-type:text/html; charset=cp1251');
}

$module_name='cackle.comments';
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/" . $module_name . "/classes/general/cackle_api.php");
class Sync {
    function Sync() {
        $cackle_api = new CackleAPI();
       // var_dump("account_api_".$_SERVER['HTTP_HOST']);die();
        $this->siteId = $cackle_api->cackle_get_param("site_id_".$_SERVER['HTTP_HOST']);
        $this->accountApiKey = $cackle_api->cackle_get_param("account_api_".$_SERVER['HTTP_HOST']);
        $this->siteApiKey = $cackle_api->cackle_get_param("site_api_".$_SERVER['HTTP_HOST']);
        //var_dump("account_api_".$_SERVER['HTTP_HOST']);
    }

    function has_next ($size_comments, $size_pagination = 100) {
        return $size_comments == $size_pagination;
    }
    function push_next_comments($mode,$comment_last_modified, $size_comments){
        $i = 1;
        while($this->has_next($size_comments)){
            if ($mode=="all_comments"){
                $response = $this->get_comments(0,$i) ;
            }
            else{
                $response = $this->get_comments($comment_last_modified,$i) ;
            }
            $size_comments = $this->push_comments($response); // get comment from array and insert it to wp db
			$i++;
        }
    }
    function init($mode = "") {

        $apix = new CackleAPI();
        $comment_last_modified = $apix->cackle_get_param("last_modified_".$_SERVER['HTTP_HOST']);

        if ($mode == "all_comments") {
            $response = $this->get_comments(0);

        }
        else {
            $response = $this->get_comments($comment_last_modified);
        }
		
        //get comments from Cackle Api for sync
        if ($response==NULL){
            return false;
        }
		$size_comments = $this->push_comments($response); // get comment from array and insert it to wp db, and return size
		if ($this->has_next($size_comments)) {
            $this->push_next_comments($mode,$comment_last_modified, $size_comments);
		}
        return "success";
    }

    function get_comments($comment_last_modified, $cackle_page = 0){
        $this->get_url = "http://cackle.me/api/3.0/comment/list.json?id=$this->siteId&accountApiKey=$this->accountApiKey&siteApiKey=$this->siteApiKey";
        $host = $this->get_url . "&modified=" . $comment_last_modified . "&page=" . $cackle_page . "&size=100";
		
        $result = file_get_contents($host);
		//print_r($result);
        return $result;
		//return false;

    }



    function to_i($number_to_format){
        return number_format($number_to_format, 0, '', '');
    }


    function cackle_json_decodes($response){

        $obj = json_decode($response,true);

        return $obj;
    }

    function filter_cp1251($string1){
        $cackle_api = new CackleAPI();
        if ($cackle_api->cackle_get_param("cackle_encoding_".$_SERVER['HTTP_HOST']) == "1"){
            $string2 = iconv("utf-8", "CP1251",$string1);
            //print "###33";
        }
        return $string2;
    }
	function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    function insert_comment($comment,$status){
	
        /*
         * Here you can convert $url to your post ID
         */
		if ($this->startsWith($comment['chan']['channel'], 'http')) {
            $url = 0;
        } else {
            $url = $comment['chan']['channel'];
        }
        
        //var_dump($comment);
        if ($comment['author']!=null){
            $author_name = ($comment['author']['name']) ? $comment['author']['name'] : "";
            $author_email=  ($comment['author']['email']) ? $comment['author']['email'] :"";
            $author_www = $comment['author']['www'];
            $author_avatar = $comment['author']['avatar'];
            $author_provider = $comment['author']['provider'];
            $author_anonym_name = "";
            $anonym_email = "";
        }
        else{
            $author_name = ($comment['anonym']['name']) ? $comment['anonym']['name']: "" ;
            $author_email= ($comment['anonym']['email']) ?  $comment['anonym']['email'] : "";
            $author_www = "";
            $author_avatar = "";
            $author_provider = "";
            $author_anonym_name = $comment['anonym']['name'];
            $anonym_email = $comment['anonym']['email'];

        }
        $get_parent_local_id = null;
        $comment_id = $comment['id'];
        $comment_modified = $comment['modified'];
        $cackle_api = new CackleAPI();
        if ($cackle_api->cackle_get_param("last_comment_".$_SERVER['HTTP_HOST'])==0){
            $cackle_api->cackle_db_prepare();
        }

        $date =strftime("%d.%m.%Y %H:%M:%S", $comment['created']/1000);
        $ip = ($comment['ip']) ? $comment['ip'] : "";
        $message = $comment['message'];
        $user_agent = 'Cackle:' . $comment['id'];


        $conn = $cackle_api->conn();
        if ($cackle_api->cackle_get_param("cackle_encoding_".$_SERVER['HTTP_HOST']) == 1){

            $conn->Query('SET NAMES cp1251');
        }
		else{
		$conn->Query('SET NAMES utf8');
		}


        //$sql = "insert into " . PREFIX ."_comments (post_id,autor,email,date,ip,text,approve,user_agent) values (:url, :author_name, :author_email, :date, :ip, :message, :status, :user_agent ) ";
	    //$q = $conn->prepare($sql);
	    $arFields=
                Array(
                    'post_id'=>($cackle_api->cackle_get_param("cackle_encoding_".$_SERVER['HTTP_HOST']) == 1) ? iconv("utf-8", "CP1251",$url) : $url,
                    'autor'=>($cackle_api->cackle_get_param("cackle_encoding_".$_SERVER['HTTP_HOST']) == 1) ? iconv("utf-8", "CP1251",$author_name) : $author_name,
                    'email'=>($cackle_api->cackle_get_param("cackle_encoding_".$_SERVER['HTTP_HOST']) == 1) ? iconv("utf-8", "CP1251",$author_email) : $author_email ,
                    'date'=>$date,
                    'ip'=>$ip,
                    'text'=>($cackle_api->cackle_get_param("cackle_encoding_".$_SERVER['HTTP_HOST']) == 1) ? iconv("utf-8", "CP1251",$message) : $message,
                    'approve'=>$status,
                    'user_agent'=>$user_agent,


                );
        global $DB;
        //$err_mess = (CForm::err_mess())."<br>Function: AddResultAnswer<br>Line: ";
        $arInsert = $DB->PrepareInsert("b_comments", $arFields);
        //var_dump($arFields);
        $strSql = "INSERT INTO b_comments (".$arInsert[0].") VALUES (".$arInsert[1].")";
        $DB->Query($strSql, false);


        $cackle_api->cackle_set_param("last_comment_".$_SERVER['HTTP_HOST'],$comment_id);
        $get_last_modified = $cackle_api->cackle_get_param("last_modified_".$_SERVER['HTTP_HOST']);
        $get_last_modified = (int)$get_last_modified;
        if ($comment['modified'] > $get_last_modified) {
            $cackle_api->cackle_set_param("last_modified_".$_SERVER['HTTP_HOST'],(string)$comment['modified']);
        }

    }

    function comment_status_decoder($comment) {
        $status;
        if (strtolower($comment['status']) == "approved") {
            $status = 1;
        }
        elseif (strtolower($comment['status'] == "pending") || strtolower($comment['status']) == "rejected") {
            $status = 0;
        }
        elseif (strtolower($comment['status']) == "spam") {
            $status = 0;
        }
        elseif (strtolower($comment['status']) == "deleted") {
            $status = 0;
        }
        return $status;
    }

    function update_comment_status($comment_id, $status, $modified, $comment_content) {
        $cackle_api = new CackleAPI();
        //print_r('comment_content='.$comment_content);
		if ($cackle_api->cackle_get_param("cackle_encoding_".$_SERVER['HTTP_HOST']) == 1){
            $comment_content = iconv("utf-8", "cp1251",$comment_content);
        }
        global $DB;

        $arFields = array(
            'approve' => $status,
            'text' => $comment_content
        );
		
        $sql = $DB->PrepareUpdate(PREFIX ."_comments",$arFields);
        $resFields = "'Cackle:$comment_id'";
        $strSql = "UPDATE b_comments SET ".$sql." WHERE `user_agent`=". $resFields;
        $DB->Query($strSql, false);
        $cackle_api->cackle_set_param("last_modified_".$_SERVER['HTTP_HOST'],$modified);

    }

    function push_comments1 ($response){
        $obj = $response['comments'];
        if ($obj) {
            foreach ($obj as $comment) {
                $cackle_api = new CackleAPI();
                $get_last_modified = $cackle_api->cackle_get_param("last_modified_".$_SERVER['HTTP_HOST']);
                $get_last_comment = $cackle_api->cackle_get_param("last_comment_".$_SERVER['HTTP_HOST']);
                //$get_last_comment = $this->db_connect("select common_value from common where `common_name` = 'last_comment'","common_value");
                //$get_last_modified = $this->db_connect("select common_value from common where `common_name` = 'last_modified'","common_value");
                if ($comment['id'] > $get_last_comment) {
                    $this->insert_comm($comment, $this->comment_status_decoder($comment));
                } else {
                    if ($get_last_modified==""){
                        $get_last_modified == 0;
                    }
                    if ($comment['modified'] > $get_last_modified) {
                        $this->update_comment_status($comment['id'], $this->comment_status_decoder($comment), $comment['modified'], $comment['message'] );
                    }
                }

            }
        }
    }


    function push_comments ($response){
	//print_r($response);
        $apix = new CackleAPI();
        $get_last_modified = $apix->cackle_get_param("last_modified_".$_SERVER['HTTP_HOST']);
        $get_last_comment = $apix->cackle_get_param("last_comment_".$_SERVER['HTTP_HOST']);
		
        $obj = $this->cackle_json_decodes($response,true);
        $obj = $obj['comments'];
		
		
        if ($obj) {
            $comments_size = count($obj);
            if ($comments_size != 0){
                foreach ($obj as $comment) {
                    if ($comment['id'] > $get_last_comment) {
						
                        $this->insert_comment($comment, $this->comment_status_decoder($comment));
						
                    } else {
                        // if ($comment['modified'] > $apix->cackle_get_param('cackle_comments_last_modified', 0)) {
                        $this->update_comment_status($comment['id'], $this->comment_status_decoder($comment), $comment['modified'], $comment['message'] );
                        // }
                    }
                }
            }
        }
        return $comments_size;

    }
}
?>