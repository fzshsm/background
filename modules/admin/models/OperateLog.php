<?php

namespace app\modules\admin\models;

use Yii;
use yii\data\Pagination;
use yii\data\SqlDataProvider;

/**
 * This is the model class for table "sr_operate_log".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $target_url
 * @property string $ip
 * @property string $action
 * @property string $note
 * @property string $create_time
 */
class OperateLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sr_operate_log';
    }

    public function getUser(){
        return $this->hasOne(User::className() , ['id' => 'uid']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'integer'],
            [['create_time'], 'safe'],
            [['target_url', 'action'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 32],
            [['note'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '日志ID',
            'uid' => '用户id',
            'target_url' => '访问的url',
            'ip' => '访问ip',
            'action' => '动作名',
            'note' => 'json格式备注字段',
            'create_time' => '创建时间',
        ];
    }

    public function createLog($uid,$targetUrl,$action,$note,$ip)
    {
        $this->uid = $uid;
        $this->target_url = $targetUrl;
        $this->action = $action;
        $this->note = $note;
        $this->ip = $ip;
        $this->create_time = time();
        return $this->save();
    }

    public function getLogList($pageSize,$action,$begin,$end,$username)
    {
        $params = [];
        if(!empty($begin)){
            $begin = strtotime($begin);
            $end = strtotime("$end +1 day");
            $params = ["between",$this::tableName().'.create_time',$begin,$end];
        }

        if(!empty($action)){
            if(empty($params)){
                $params = [$this::tableName().'.action' => $action];
            }else{
                $params = ['and',$params,[$this::tableName().'.action' => $action]];
            }
        }

        if(!empty($username)){
            $user = User::tableName();
            if(empty($params)){
                $params = [$user.'.username' => $username];
            }else{
                $params = ['and',$params,[$user.'.username' => $username]];
            }
        }

        $operateLog = OperateLog::tableName();

        $query = $this::find()
            ->where($params)
            ->JoinWith('user')
            ->orderBy("$operateLog.create_time DESC");

        $data = ['data' =>[],'count' => 0];
        if($query){
            $count = $query->count();

            $pagination = new Pagination(['totalCount' => $count,'pageSize' => $pageSize]);

            $response = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

            $data = ['data' => $response,'count' => $count];
        }

        return $data;
    }

    public function getNoteById($id)
    {
        return $query = $this::findOne($id);
    }
}
