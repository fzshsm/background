<?php

namespace app\controllers;

use yii\helpers\Json;

class ErrorController extends \app\controllers\Controller{
    
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $exception = \Yii::$app->errorHandler->exception;
        if($request->isAjax){
            $error = [
                    'status' => 'error',
                    'code' => $exception->statusCode,
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine()
            ];
            return Json::encode($error);
        }else{
            return $this->render('index' , ['error' => $exception]);
        }
    }

}
