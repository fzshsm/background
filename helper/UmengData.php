<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zhangxinlong
 * Date: 2018/2/26
 * Time: 15:08
 */

namespace app\helper;


use yii\httpclient\Client;

class UmengData{

    CONST ANDROID_APP_ID = '59f03b19bc38842e5e0004bd';
    CONST IOS_APP_ID = '59f03b19bc38842e5e0004be';
    
    
    /**
     * 请求图形数据
     * @param $beginDate   2018-01-01
     * @param $endDate     2018-01-31
     * @param string $from  ios , android
     * @return array or false
     */
    public static function requestData($beginDate , $endDate , $from = ''){
        $perpage = ceil( (strtotime($endDate) - strtotime($beginDate)) / 86400 ) + 1;
        $url = "http://mobile.umeng.com/apps/load_group_trend_table.json";
        $data = [
            'start_date' => $beginDate,
            'end_date' => $endDate,
            'versions[]' => '',
            'channels[]' => '',
            'segments[]' => '',
            'time_unit' => 'daily',
            'stats' => 'group_trend',
            'page' => 1,
            'per_page' => $perpage
        ];
        if(!empty($from)){
            $appId = strtolower($from) == 'ios' ? self::IOS_APP_ID : self::ANDROID_APP_ID;
            $data['app_tag_id'] = $appId;
        }
        $client = new Client();
        $response = $client->get($url , $data)->send();
        if(!empty($response)){
            $jsonData = json_decode($response->getContent() , true);
            if(!empty($jsonData) && $jsonData['result'] == 'success'){
                krsort($jsonData['stats']);
                return $jsonData['stats'];
            }
        }
        return false;
    }
    
}