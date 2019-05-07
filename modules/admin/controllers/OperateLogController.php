<?php
namespace app\modules\admin\controllers;

use app\controllers\Controller;
use app\modules\admin\models\Log;
use app\modules\admin\models\OperateLog;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

/**
 * Default controller for the `User` module
 */
class OperateLogController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $pageSize = $request->get('pageSize', 30);
        $date = $request->get('date');
        $searchType = $request->get('searchType');
        $content = $request->get('content');
        $action  = '';
        $username = '';

        if($searchType == 'action'){
            $action = trim($content);
        }elseif ($searchType == 'username'){
            $username = trim($content);
        }

        $begin = $end = '';
        if(!empty($date)){
            list($begin , $end) = explode('至' , $date);
        }

        $operateLog = new OperateLog();

        $response = $operateLog->getLogList($pageSize,$action,$begin,$end,$username);

        $logData = $response['data'];
        $results = [];
        if(!empty($logData) && is_array($logData)){
            foreach ($logData as $row){

                $results[] = [
                    'id' => $row->id,
                    'username' => $row->user['username'],
                    'target_url' => $row->target_url,
                    'action' => $row->action,
                    'ip' => $row->ip,
                    'create_time' => date('Y-m-d H:i:s',$row->create_time),
                ];
            }
        }

        $dataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['id'],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
                'totalCount' => $response['count']
            ]
        ]);
        $dataProvider->setModels($results);
        $dataProvider->setTotalCount($response['count']);

        return $this->render('index',['dataProvider' => $dataProvider]);
    }

    public function actionNote($id)
    {
        $operateLog = new OperateLog();

        $response = $operateLog->getNoteById($id);

        if($response){
            $data = ['status' => 'success' , 'message' => '查询成功' ,'data' => Json::decode($response->note)];
        }else{
            $data = ['status' => 'error' , 'message' => '查询失败' ];
        }

        return Json::encode($data);
    }
}
