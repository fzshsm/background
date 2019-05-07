<?php
/**
 * Created by IntelliJ IDEA.
 * User: Raytine
 * Date: 2017/8/7
 * Time: 14:47
 */

namespace app\components;


use Upyun\Config;
use Upyun\Upyun;
use yii\helpers\Url;

class Upaiyun extends Upyun {
    
    const BUCKET = 'star-rank';
    const OPERATOR = 'manager';
    const PASSWORD = 'starrank123';
    const URL = 'http://star-rank.b0.upaiyun.com';
    

    public function __construct(){
        $config = new Config( self::BUCKET , self::OPERATOR , self::PASSWORD);
        if(YII_DEBUG){
            $config->debug = fopen(Url::to('@app/runtime/upyun_debug.txt') , 'a+');
        }
        parent::__construct($config);
    }
    
    
}