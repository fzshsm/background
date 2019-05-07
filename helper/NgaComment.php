<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2017/12/12
 * Time: 14:20
 */
namespace app\helper;

use yii\helpers\Json;
use yii\httpclient\Client;

class NgaComment {
    
    const PAGESIZE = 20;
    
    public function getCommentList($requestUrl , $page = 1){
        $comment = [];
        $content = $this->getContent($requestUrl);
        preg_match_all('/class\=\'postrow[\s\S]*?<\/tr/i' , $content , $sourceFloorList);
        foreach($sourceFloorList[0] as $key => $floorContent){
            if($page == 1 && $key == 0){
                continue;
            }
            preg_match('/uid=(\d+)/i' , $floorContent , $matchUser);
            $userInfo = $this->getUserInfo($matchUser[1]);
            preg_match('/reply\s*time[\'|\"]>([^<]*)[\s|\S]*postcontent\s*ubbcode[\'|\"]>(.*?)<\/span/i' , $floorContent , $match);
            if(!empty($match[1]) && !empty($match[2])){
                $content = $this->filter($match[2]);
                $comment[] = [
                    'floor' => ($page - 1) * self::PAGESIZE + $key,
                    'nickname' => $userInfo['nickname'],
                    'avatar' => !empty($userInfo['avatar']) ? $userInfo['avatar'] : $this->getRandromHeadImage(),
                    'content' => !empty($content) ? $content : '围观~',
                    'source' => 'nga',
                    'create_time' => strtotime($match[1]),
                    'create_user_id' => $matchUser[1],
                    'create_user_ip' => '127.0.0.1'
                ];
            }
        }
        return $comment;
    }
    
    
    protected function getContent($requestUrl){
        $client = new Client();
        return iconv('gbk' , 'utf-8//IGNORE' , $client->get($requestUrl)->send()->getContent());
    }
    
    protected function filter($content){
        $pattern = [
            '/\[quote\].*?\[\/quote\]<br\/><br\/>/i',
            '/\[s:[^\]]*\]/i',
            '/\[b\].*?\[\/b\]/i',
            '/\[del\].*?\[\/del\]/i',
            '/<br\/>/i',
            '/<br>/i'
        ];
        $content = preg_replace($pattern , "" , $content);
        return $content;
    }
    
    protected function getRandromHeadImage(){
        $num = rand(1 , 1209);
        return "http://starrank-1254164914.file.myqcloud.com/random_head/{$num}.jpg";
    }
    
    protected function getRandNickname(){
    
    }
    
    protected function getAvatar($userAvatar){
        $jsonParse = json_decode($userAvatar , true);
        if($jsonParse == false){
            $avatar = $userAvatar;
        }else{
            if(stripos($jsonParse[0] , 'http') !== false){
                $avatar = $jsonParse[0];
            }else{
                $avatar = $this->getRandromHeadImage();
            }
        }
        return $avatar;
    }
    
    protected function getUserInfo($uid){
//        $requestUrl = "http://rsync3.ngacn.cc/nuke.php?__lib=ucp&__act=get&lite=js&&uid=39794957";
        $requestUrl = "http://rsync3.ngacn.cc/nuke.php?__lib=ucp&__act=get&lite=js&&uid={$uid}";
        $response = str_replace('window.script_muti_get_var_store=' , "" , $this->getContent($requestUrl));
        $userInfo = Json::decode($response);
        $avatar = $this->getAvatar($userInfo['data'][0]['avatar']);
        return [
            'nickname' => (string) $userInfo['data'][0]['username'],
            'avatar' => $avatar
        ];
    }
}