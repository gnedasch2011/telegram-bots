<?php

namespace frontend\modules\expenses\models;

use Yii;

/**
 * This is the model class for table "users_chat".
 *
 * @property int $id
 * @property int $chat_id
 * @property int $users_id
 *
 * @property Expenses[] $expenses
 * @property UserGroup[] $userGroups
 * @property Users $users
 */
class UsersChat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'users_id'], 'required'],
            [['chat_id', 'users_id'], 'integer'],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['users_id' => 'id']],
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
            'users_id' => 'Users ID',
        ];
    }

    /**
     * Gets query for [[Expenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::class, ['users_chat_id' => 'id']);
    }

    /**
     * Gets query for [[UserGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroups()
    {
        return $this->hasMany(UserGroup::class, ['users_chat_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::class, ['id' => 'users_id']);
    }
}
