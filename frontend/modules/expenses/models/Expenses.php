<?php

namespace frontend\modules\expenses\models;

use Yii;

/**
 * This is the model class for table "expenses".
 *
 * @property int $id
 * @property int $value
 * @property string|null $created_at
 * @property int $users_chat_id
 *
 * @property UsersChat $usersChat
 */
class Expenses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expenses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'users_chat_id'], 'required'],
            [['value', 'users_chat_id'], 'integer'],
            [['created_at'], 'string', 'max' => 45],
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
            'value' => 'Value',
            'created_at' => 'Created At',
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
