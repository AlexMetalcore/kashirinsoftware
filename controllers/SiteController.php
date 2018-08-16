<?php

namespace app\controllers;

use app\models\AWSSource;
use app\models\Maintenance;
use app\modules\patient\components\Patient;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\components\AWS;

class SiteController extends \app\components\Controller
{
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionMaintenance(){
    	if (Yii::$app->isMaintenance) {
		    if (\Yii::$app->isDbFree){
			    $this->layout = 'maintenance-db-free';
		    } else {
			    $this->layout = 'maintenance';
		    }

		    if (Yii::$app->request->get('maintenance', false) === 'off'){
			    Maintenance::deleteAll();
			    $this->redirect('/');
			    return '';
		    }
		    return $this->render('maintenance');
	    }

  		$this->redirect('/');
	    return '';
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!Yii::$app->patient->isGuest && !$model->forgot) {
	            $url = \Yii::$app->session->get('loginUrl', \Yii::$app->guestSession->get('loginUrl', '/subscriber-home'));
	            \Yii::$app->guestSession->remove('loginUrl');
                return $this->redirect($url);
            }
        	if ($model->forgot){

                    \Yii::$app->mailer
                            ->compose('register/forgot', ['model' => $model])
                            ->setFrom(\Yii::$app->params['robotEmail'])
                            ->setTo($model->forgotModel->email)
                            ->setSubject('Reset your password')
                            ->send();
                    
                    $patient = $model->forgotModel;
                    $patient->link_exp_date = new \yii\db\Expression('NOW() + INTERVAL 1 DAY');
                    $patient->save(false);

                    return $this->render('forgot', [
                            'model' => $model,
                    ]);
	        } else {
		        $url = \Yii::$app->session->get('loginUrl', \Yii::$app->guestSession->get('loginUrl', '/subscriber-home'));
		        \Yii::$app->guestSession->remove('loginUrl');
		        return $this->redirect($url);
	        }
        }
	    return $this->render('login', [
		    'model' => $model,
	    ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
    	Yii::$app->patient->logout();
        return $this->redirect('/');
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

    public function actionFaq()
    {
        return $this->render('faq');
    }

    public function actionPage($page)
    {
        return $this->render('page', ['page' => $page]);
    }

    public function actionAws(){

        return $this->render('awsview');
    }

//    public function actionT(){
//    	var_dump();
//    }
}
