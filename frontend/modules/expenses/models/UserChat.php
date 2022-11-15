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
            [['chat_id', 'user_id'], 'required'],
            [['chat_id', 'user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => user::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => 'user ID',
        ];
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
        return $this->hasOne(user::class, ['id' => 'user_id']);
    }
}
