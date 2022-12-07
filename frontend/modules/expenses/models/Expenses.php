<?php

namespace frontend\modules\expenses\models;

use Yii;

/**
 * This is the model class for table "expenses".
 *
 * @property int $id
 * @property int $value
 * @property string|null $created_at
 * @property string|null $comment
 * @property int $users_chat_id
 * @property int $user_id
 *
 * @property userChat $userChat
 */
class Expenses extends IncludeDataBase
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expenses';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),  ['user_id' => 'user_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'users_chat_id', 'user_id'], 'required'],
            [['value', 'users_chat_id', 'created_at'], 'integer'],
            [['comment'], 'string'],
            [['users_chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserChat::class, 'targetAttribute' => ['users_chat_id' => 'chat_id']],
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
            'users_chat_id' => 'user Chat ID',
        ];
    }

    /**
     * Gets query for [[userChat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserChat()
    {
        return $this->hasOne(UserChat::class, ['id' => 'users_chat_id']);
    }

    public static function addExpenses($data, $user_chat)
    {
        $expenses = new self;
        $value = preg_replace('/[^0-9]/', '', $data['message']['text']);

        $comment = preg_replace("/[^а-яёa-z, ]/iu", '', $data['message']['text']);

        $expenses->value = $value;
        $expenses->comment = $comment ?? null;
        $expenses->created_at = $data['message']['date'];
        $expenses->users_chat_id = $user_chat->chat_id;
        $expenses->user_id = $user_chat->users_id;

        if (!$expenses->save()) {
            return false;
        }

        return false;

    }
}
