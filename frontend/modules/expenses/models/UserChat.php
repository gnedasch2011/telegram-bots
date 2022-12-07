<?php

namespace frontend\modules\expenses\models;

use Yii;

/**
 * This is the model class for table "user_chat".
 *
 * @property int $id
 * @property int $chat_id
 * @property int $user_id
 *
 * @property Expenses[] $expenses
 * @property UserGroup[] $userGroups
 * @property user $user
 */
class UserChat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id'], 'required'],
            [['chat_id'], 'integer'],
            [['users_id'], 'safe'],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['users_id' => 'user_id']],
        ];
        ['users_id', 'unique', 'targetAttribute' => ['users_id', 'chat_id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'users_id' => 'user ID',
        ];
    }


    public function getUsersExpenses()
    {
        $usersExpenses = Expenses::find()
            ->select('expenses.user_id, sum(value) as commonEx, 	CONCAT_WS(" ", user.first_name, user.last_name) AS `fullName`')
            ->joinWith('user')
            ->where(['users_chat_id'=>$this->chat_id])
           ->asArray()
            ->groupBy('expenses.user_id')
           ->all()
        ;

        return $usersExpenses;

    }

    public function getCommonExpenses()
    {
        $commonExpenses = Expenses::find()
            ->select('sum(value) as commonEx')
            ->where(['users_chat_id'=>$this->chat_id])
             ->asArray()
           ->all()
        ;
        return $commonExpenses;

    }
    /**
     * Gets query for [[Expenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::class, ['user_chat_id' => 'id']);
    }

    /**
     * Gets query for [[UserGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroups()
    {
        return $this->hasMany(UserGroup::class, ['user_chat_id' => 'id']);
    }

    /**
     * Gets query for [[user]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getuser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function initChat($data, $user)
    {
        if (!isset($data['message']['chat'])) {
            return false;
        }

        $userChat = self::findOne([
            'chat_id' => $data['message']['chat']['id'],
            'users_id' => $user->user_id,
        ]);

        if ($userChat) {
            return $userChat;
        }


        if (!$userChat) {
            $userChat = new self();
            $userChat->chat_id = $data['message']['chat']['id'];
            $userChat->users_id = $user->user_id;
            $userChat->save();
        }

        return $userChat;
    }
}
