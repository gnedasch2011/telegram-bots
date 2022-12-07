<?php

namespace frontend\modules\expenses\models;

use Yii;

/**
 * This is the model class for table "user_group".
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_id_link
 * @property int $user_chat_id
 *
 * @property UserChat $userChat
 */
class UserGroup extends IncludeDataBase
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
            [['user_id', 'user_id_link', 'user_chat_id'], 'required'],
            [['user_id', 'user_id_link', 'user_chat_id'], 'integer'],
            [['user_chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserChat::class, 'targetAttribute' => ['user_chat_id' => 'id']],
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
            'user_chat_id' => 'User Chat ID',
        ];
    }

    /**
     * Gets query for [[UserChat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserChat()
    {
        return $this->hasOne(UserChat::class, ['id' => 'user_chat_id']);
    }
}
