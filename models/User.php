<?php

namespace app\models;

use app\modules\user\api\User as UserData;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        0 => [
            'id' => 0,
            'username' => 'founder',
            'password' => 'founder',
            'authKey' => 'founder-0-auth-key',
            'accessToken' => 'founder-access-0-token',
        ]
    ];


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $session = \Yii::$app->getSession()->get('user');
        return !empty($session) ? new static($session) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null){
//         $user = new UserData();
//         $users = self::$users;
//         $users[$user['id']] = $user;
//         foreach (self::$users as $user) {
//             if ($user['accessToken'] === $token) {
//                 return new static($user);
//             }
//         }
//         return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username , $password){
        $user = null;
        if($username == self::$users[0]['username']){
            $user = new static(self::$users[0]);
            \Yii::$app->getSession()->set('user', $user);
        }else{
            $userModel = new UserData();
            $userData = $userModel->login($username , $password);
            \Yii::$app->getSession()->set('user', $userData);
            $user = new static($userData);
        }
        return $user;
    }

    public function findByUrlUserName($username,$password){
        $params = ['username' => $username , 'password' =>$password,'status' => 'normal'];

        $response = (new \yii\db\Query())
            ->select(['id'])
            ->from('sr_user')
            ->where($params)
            ->one();
        $data = [];
        if($response !== false){
            $data = [
                'id' => isset($response['id']) ? $response['id'] : 1,
                'username' => $username,
                'password' => $password,
                'authKey' =>  $username . '-auth-' . md5(time()),
                'accessToken' =>  $username . '-' . md5(\Yii::$app->request->getUserIP() . \Yii::$app->formatter->asDate(time()))
            ];
        }
        $user = new static($data);
        \Yii::$app->getSession()->set('user', $user);
        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getUsername(){
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
