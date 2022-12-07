<?php

namespace frontend\modules\expenses\models;


class IncludeDataBase extends \yii\db\ActiveRecord
{
    public static function getDb() {
        return \Yii::$app->get('db_expenses');
    }
}