<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
            'plugin/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css',
            'css/bootstrap.css',
            'css/font-icons/entypo/css/entypo.css',
            'css/font-icons/font-awesome/css/font-awesome.min.css',
            'css/neon-core.css',
            'css/neon-theme.css',
            'css/neon-forms.css',
            'css/custom.css'
    ];
    public $js = [
            
    ];
    public $depends = [
//         'yii\web\YiiAsset',
//         'yii\bootstrap\BootstrapAsset',
    ];
    
    public static function registerCss($view , $css){
        $view->registerCssFile($css , [AppAsset::className() , 'depends' => 'app\assets\AppAsset']);
    }
    
    public static function registerJs($view , $js , $position = ''){
        if(!empty( $position)){
            $setPostion = ['position' => $position];
        }else{
            $setPostion = [AppAsset::className() , 'depends' => 'app\assets\AppAsset'];
        }
        $view->registerJsFile($js, $setPostion);
    }
}
