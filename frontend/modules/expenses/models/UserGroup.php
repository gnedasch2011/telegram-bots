<?php

namespace frontend\modules\expenses\models;

use Yii;

/**
 * This is the model class for table "user_group".
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_id_link
 * @property int $users_chat_id
 *
 * @property UsersChat $usersChat
 */
class UserGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_id_link', 'users_chat_id'], 'required'],
            [['user_id', 'user_id_link', 'users_chat_id'], 'integer'],
            [['users_chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsersChat::class, 'targetAttribute' => ['users_chat_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'user_id_link' => 'User Id Link',
            'users_chat_id' => 'Users Chat ID',
        ];
    }

    /**
     * Gets query for [[UsersChat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersChat()
    {
        return $this->hasOne(UsersChat::class, ['id' => 'users_chat_id']);
    }
}
