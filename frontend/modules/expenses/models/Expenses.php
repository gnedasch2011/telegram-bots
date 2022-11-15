<?php

namespace frontend\modules\expenses\models;

use Yii;

/**
 * This is the model class for table "expenses".
 *
 * @property int $id
 * @property int $value
 * @property string|null $created_at
 * @property int $user_chat_id
 *
 * @property userChat $userChat
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
            [['value', 'user_chat_id'], 'required'],
            [['value', 'user_chat_id'], 'integer'],
            [['created_at'], 'string', 'max' => 45],
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
            'value' => 'Value',
            'created_at' => 'Created At',
            'user_chat_id' => 'user Chat ID',
        ];
    }

    /**
     * Gets query for [[userChat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserChat()
    {
        return $this->hasOne(UserChat::class, ['id' => 'user_chat_id']);
    }
}
