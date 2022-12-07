<?php

namespace frontend\modules\expenses\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use frontend\modules\expenses\models\Expenses;
use frontend\modules\expenses\models\User;
use frontend\modules\expenses\models\UserChat;
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

    public $bot_api_key = '5762279127:AAGpWwnl6ymE0vh8Crgfsydw8wtC6ryjUvc';
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
            if (isset($data['message']['from']['id'])) {
                //берём юзера, если его нет, то создаём
                $user = User::initUser($data);

                //проверяем привязку к чату, если нет то создаём привязку
                $user_chat = UserChat::initChat($data, $user);

                //забираем данные расходов по маске число комментарий
                $res = Expenses::addExpenses($data, $user_chat);

                //TODO сформировать ответ в виде таблицы и итогом кто сколько должен в общак или сколько должны ему, а так же кнопками удалить последнюю запись и подсчитать итог месяца, так же сделать возможность установки даты расчёта

                // Настройки вывода для пользователя, типа конфиг для пользователя, загрузка перез отдачей view
                //Модель ConfigView


                $commonExpenses = $user_chat->commonExpenses;
                $usersExpenses = $user_chat->usersExpenses;
                $countUsers = count($usersExpenses);

                foreach ($usersExpenses as &$user) {
                    $user['commonEx'] = round($user['commonEx']-($commonExpenses[0]['commonEx'] / $countUsers));

                }
                //Кнопки <сделать рассчёт сейчас>

                //Кнопки <вывести все траты>
                //Коммент сумма дата, по дате

                // А если генерировать jpg, графиками и таблицами

                $view = $this->renderPartial('resultExpenses', [
                    'commonExpenses' => $commonExpenses,
                    'usersExpenses' => $usersExpenses,
                ]);
                //return $this->renderAjax('view');

                $result = Request::sendMessage([
                    'chat_id' => $user_chat->chat_id,
                    'text' => $view,
                    'parse_mode' => 'html',
                ]);
                
                echo "<pre>"; print_r($result);die();

            }

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

    public function actionView()
    {
        Yii::$app->response->headers->set('Content-type', 'image/png');

        $largeurImage = 480;
        $hauteurImage = 640;
        $im = ImageCreate($largeurImage, $hauteurImage) or die ("Ошибка при создании изображения");
        $noir = ImageColorAllocate($im, 0, 0, 0);

        // проводим горизонтальную линию, ось абсцисс (время)
        ImageLine($im, 10, $hauteurImage - 10, $largeurImage - 10, $hauteurImage - 10, $noir);

        // проводим вертикальную линию, чтобы ось ординат (число посещений)
        ImageLine($im, 10, 10, 10, $hauteurImage - 10, $noir);

        ImagePng($im);
    }

}
