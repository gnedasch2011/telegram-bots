<?php

namespace frontend\modules\expenses\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public $enableCsrfValidation = false;

    public $bot_api_key  = '5762279127:AAGpWwnl6ymE0vh8Crgfsydw8wtC6ryjUvc';
    public $bot_username = 'generalExpensesBot';

    public function actionIndex()
    {
        //Имитация разных пользователей

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        
        try {
            // Create Telegram API object
            $telegram = new Telegram($this->bot_api_key, $this->bot_username);

            // Handle telegram webhook request
           // $telegram->handle();

            //берём юзера, если его нет, то создаём
            echo "<pre>"; print_r($data);die();
            
            
//            $result = Request::sendMessage([
//                'chat_id' => 725086949,
//                'text'    => 'Your utf8 text 😜 ...',
//            ]);

        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            // Silence is golden!
            // log telegram errors
             echo $e->getMessage();
        }

    }

}
