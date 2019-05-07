<?php

namespace app\modules\statistics;

/**
 * analyzestatistics module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\statistics\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
//        AppAsset::registerJs($this, '@web/plugin/datatables/datatables.min.js');
        // custom initialization code goes here
    }
}
