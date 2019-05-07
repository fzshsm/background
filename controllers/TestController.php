<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/12/12
 * Time: 14:56
 */

namespace app\controllers;


use app\helper\NgaComment;
use app\helper\UmengData;
use yii\helpers\Json;
use yii\httpclient\Client;

class TestController extends \yii\web\Controller {
    
    public function actionIndex(){
        
//        $requestUrl = "http://rsync3.ngacn.cc/nuke.php?__lib=ucp&__act=get&lite=js&&uid=41525089";
//        $client = new Client();
//        $content =  iconv('gbk' , 'utf-8' , $client->get($requestUrl)->send()->getContent());
//        $response = str_replace('window.script_muti_get_var_store=' , "" , $content);
//        $userInfo = json_decode($response , true);
//        var_dump($userInfo);
//        $avatar = json_decode($userInfo['data'][0]['avatar']) == false ? $userInfo['data'][0]['avatar'] : json_decode($userInfo['data'][0]['avatar']);
//        var_dump($avatar);
//        exit;
//        $comment = new NgaComment();
//        $requestUrl = "http://rsync3.ngacn.cc/read.php?tid=12847655&page=2";
//        $commentList = $comment->getCommentList($requestUrl , 2);
//        var_dump($commentList);
        
        $data = UmengData::requestData('2018-01-01' , '2018-02-25' , 'android');
        var_dump($data);
    
    }
    
}