<?php

namespace frontend\modules\expenses\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $language_code
 * @property int|null $is_bot
 *
 * @property UsersChat[] $usersChats
 */
class User extends IncludeDataBase
{

    

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required',],
            [['is_bot'], 'default', 'value' => 0],
            [['username', 'first_name', 'last_name', 'language_code'], 'string', 'max' => 45],
            ['username', 'unique', 'targetAttribute' => 'user_id']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'user_id',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'language_code' => 'Language Code',
            'is_bot' => 'Is Bot',
        ];
    }

    /**
     * Gets query for [[UserChats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserChats()
    {
        return $this->hasMany(UserChat::class, ['user_id' => 'id']);
    }

    public static function initUser($data)
    {
        if (!isset($data['message']['from']['id'])) {
            return false;
        }

        $user = self::findOne([
            'user_id' => $data['message']['from']['id']
        ]);

        if($user){
            return $user;
        }

        if (!$user) {
            $user = new self;
            $user->attributes = $data['message']['from'];
            $user->user_id = $data['message']['from']['id'];
            
            $user->save();
        }

        return $user;
    }
}
