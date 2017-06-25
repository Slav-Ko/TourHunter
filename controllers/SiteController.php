<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\Transact;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
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

    public function actionTransactions()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model=new Transact();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $user = User::findByUsername($model->username);

            if ($user == null)
                $user = User::createUser($model->username);

            /* debet plus */
            $user->balance=$user->balance+$model->amount;
            $user->save();

            /* credit minus */
            $from=User::findOne([ 'id' => Yii::$app->user->id ]);
            $from->balance=$from->balance-$model->amount;
            $from->save();

            /* create transaction */
            $model->debet=$user->id;
            $model->credit=Yii::$app->user->id;
            $model->time=time();
            $model->save();

            $model=new Transact();
        }

        $query = Transact::find()
            ->where('debet='.Yii::$app->user->id)
            ->orWhere('credit='.Yii::$app->user->id);

        $pages = new Pagination([
            'totalCount' => $query->count(), 
            'pageSize' => 10,
            'pageSizeParam' => false, 
            'forcePageParam' => false
        ]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination' => $pages,
        ]);

        return $this->render('transact', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $query = User::find()->where('status='.User::STATUS_ACTIVE);

        $pages = new Pagination([
            'totalCount' => $query->count(), 
            'pageSize' => 10,
            'pageSizeParam' => false, 
            'forcePageParam' => false
        ]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination' => $pages,
        ]);

        return $this->render('index',compact('dataProvider','pages'));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
