<?php

namespace app\components;

include \Yii::getAlias('@app/vendor/qcloudcos/include.php');

use QCloud\Cos\Api;

class QCloudCos extends Api {
    
    const BUCKET_NAME = 'starrank';
    const APP_ID = '1254164914';
    const SECRET_ID = 'AKIDrZ4NSKuRgldIjxsmMjsoW0KRN8g050f6';
    const SECRET_KEY = 'MVRAdqmRqzrVu3vXtzpPjnb9QRChvsZ2';
    const URL = 'http://starrank-1254164914.cossh.myqcloud.com';
    const CDNURL = 'http://starrank-1254164914.file.myqcloud.com';
    
    
    
    
    public function __construct(){
        
        $config = [
            'app_id' => self::APP_ID,
            'secret_id' => self::SECRET_ID,
            'secret_key' => self::SECRET_KEY,
            'region' => 'sh',
            'timeout' => 60
        ];
        parent::__construct($config);
    }
}